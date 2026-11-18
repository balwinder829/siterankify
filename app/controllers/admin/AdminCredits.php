<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Models\Plan;

defined('ALTUMCODE') || die();

class AdminCredits extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['status', 'plan_id'], ['name', 'email'], ['user_id', 'email', 'datetime', 'name']));
        $filters->set_default_order_by('user_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `users` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/credits?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $users = [];
        $users_result = database()->query("
            SELECT
                *
            FROM
                `users`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $users_result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);
            $users[] = $row;
        }

        /* Requested plan details */
        $plans = (new \Altum\Models\Plan())->get_plans();
        $plans['free'] = (new Plan())->get_plan_by_id('free');
        $plans['custom'] = (new Plan())->get_plan_by_id('custom');

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'users' => $users,
            'plans' => $plans,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/credits/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function update() {

        if(empty($_POST)) {
            redirect('admin/credits');
        }

        $user_id = (int) $_POST['user_id'];
        
        //ALTUMCODE:DEMO if(DEMO) { Alerts::add_error('This command is blocked on the demo.'); redirect('admin/credits'); }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('admin/credits');
        }

        if(!$user = db()->where('user_id', $user_id)->getOne('users')) {
            redirect('admin/credits');
        }


        $extra_views = isset($_POST['extra_report_views']) ? (int) $_POST['extra_report_views'] : 0;
        $extra_pdf = isset($_POST['extra_pdf_downloads']) ? (int) $_POST['extra_pdf_downloads'] : 0;

        $update_data = [
            'extra_report_views' => $extra_views,
            'extra_pdf_downloads' => $extra_pdf,
        ];
        
        if (isset($_POST['reset_usage']) && $_POST['reset_usage'] == '1') {
            $update_data['audit_audits_current_month'] = 0;
            
            /* Reset view and download tracking */
            db()->where('ip_md5', (string)$user_id)->where('type', 'report_view_account')->delete('ip_tracking');
            db()->where('ip_md5', (string)$user_id)->where('type', 'report_download_account')->delete('ip_tracking');
        }

        /* Update the user */
        db()->where('user_id', $user_id)->update('users', $update_data);

        /* Send email to user */
        $email_template = get_email_template(
            [],
            'Your Account Credits Have Been Updated',
            [
                '{{NAME}}' => $user->name,
                '{{EXTRA_VIEWS}}' => $extra_views,
                '{{EXTRA_PDF}}' => $extra_pdf
            ],
            '<h2>Credits Updated</h2>
            <p>Hello {{NAME}},</p>
            <p>Your extra account credits have been updated by an administrator. Here are your extra credits on top of your plan limits:</p>
            <ul>
                <li><strong>Extra Report Views:</strong> {{EXTRA_VIEWS}}</li>
                <li><strong>Extra PDF Downloads:</strong> {{EXTRA_PDF}}</li>
            </ul>
            <p>Thank you for using our service.</p>'
        );

        send_mail($user->email, $email_template->subject, $email_template->body);

        Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $user->name . '</strong>'));

        redirect('admin/credits');
    }
}
