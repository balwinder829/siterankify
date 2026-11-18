<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

defined('ALTUMCODE') || die();

class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty(settings()->main->index_url)) {
            header('Location: ' . settings()->main->index_url); die();
        }

        /* Set meta tags */
        /* Set meta tags */
        $system_page_seo = settings()->system_pages_seo->home ?? null;
        \Altum\Title::set($system_page_seo->title ?: (settings()->index->title ?? settings()->main->title));
        \Altum\Meta::$description = $system_page_seo->description ?: (settings()->index->description ?? settings()->main->description);
        \Altum\Meta::$keywords = $system_page_seo->keywords ?: (settings()->index->keywords ?? settings()->main->keywords);
        \Altum\Meta::set_robots(($system_page_seo->is_indexable ?? true) ? 'index, follow' : 'noindex, nofollow');

        /* Opengraph image */
        if(settings()->main->opengraph) {
            \Altum\Meta::set_social_image(\Altum\Uploads::get_full_url('opengraph') . settings()->main->opengraph);
        }

        /* Plans View */
        $view = new \Altum\View('partials/plans', (array) $this);
        $this->add_view_content('plans', $view->run());

        if(settings()->main->display_index_latest_blog_posts) {
            $language = \Altum\Language::$name;

            /* Blog posts query */
            $blog_posts_result_query = "
                SELECT * 
                FROM `blog_posts`
                WHERE (`language` = '{$language}' OR `language` IS NULL) AND `is_published` = 1 
                ORDER BY `blog_post_id` DESC
                LIMIT 3
            ";

            $blog_posts = \Altum\Cache::cache_function_result('blog_posts?hash=' . md5($blog_posts_result_query), 'blog_posts', function() use ($blog_posts_result_query) {
                $blog_posts_result = database()->query($blog_posts_result_query);

                /* Iterate over the blog posts */
                $blog_posts = [];

                while($row = $blog_posts_result->fetch_object()) {
                    /* Transform content if needed */
                    $row->content = json_decode($row->content) ? convert_editorjs_json_to_html($row->content) : output_blog_post_content($row->content);

                    $blog_posts[] = $row;
                }

                return $blog_posts;
            });
        }

        $tools = require APP_PATH . 'includes/tools.php';
        $enabled_tools = count(array_filter((array) settings()->tools->available_tools));

        /* Check if the cache exists */
        $cache_instance = cache()->getItem('index_stats');

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $total_websites = database()->query("SELECT MAX(`website_id`) AS `total` FROM `websites`")->fetch_object()->total ?? 0;
            $total_audits = database()->query("SELECT MAX(`audit_id`) AS `total` FROM `audits`")->fetch_object()->total ?? 0;

            $stats = [
                'total_websites' => $total_websites,
                'total_audits' => $total_audits,
            ];

            /* Save to cache */
            cache()->save($cache_instance->set($stats)->expiresAfter(3600));

        } else {

            /* Get cache */
            $stats = $cache_instance->get();

        }

        extract($stats);

        $testimonials = [];
        if(settings()->main->display_index_testimonials) {
            $testimonials_result = database()->query("SELECT * FROM `testimonials` ORDER BY `testimonial_id` ASC");
            if($testimonials_result) {
                while($row = $testimonials_result->fetch_object()) {
                    $dummy_photos = [
                        1 => '1534528741775-53994a69daeb',
                        2 => '1507003211169-0a1dd7228f2d',
                        3 => '1500648767791-00dcc994a43e',
                        4 => '1494790108377-be9c29b29330',
                        5 => '1522075469751-3a6694fb2f61',
                        0 => '1517841905240-472988babdf9',
                    ];
                    $photo_key = (int) ($row->testimonial_id % 6);
                    $photo_id = $dummy_photos[$photo_key] ?? '1534528741775-53994a69daeb';

                    $avatar_url = $row->image ? \Altum\Uploads::get_full_url('testimonials') . $row->image : 'https://images.unsplash.com/photo-' . $photo_id . '?w=150&h=150&fit=crop&crop=faces&auto=format&q=80';

                    $testimonials[] = [
                        'id' => $row->testimonial_id,
                        'name' => $row->name,
                        'attribute' => $row->attribute,
                        'text' => $row->text,
                        'image' => $avatar_url,
                    ];
                }
            }
        }

        /* Main View */
        $data = [
            'tools' => $tools,
            'enabled_tools' => $enabled_tools,
            'total_websites' => $total_websites,
            'total_audits' => $total_audits,
            'blog_posts' => $blog_posts ?? [],
            'testimonials' => $testimonials,
        ];

        $view = new \Altum\View('index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
