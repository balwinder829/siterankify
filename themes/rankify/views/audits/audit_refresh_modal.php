<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="audit_refresh_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-retweet text-dark mr-2"></i>
                        <?= l('audit_refresh_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('audit_refresh_modal.subheader') ?></p>

                <form name="audit_refresh_modal" method="post" action="<?= url('audit-refresh') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="audit_id" value="" />

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    
/* On modal show load new data */
    $('#audit_refresh_modal').on('show.bs.modal', event => {
        let id = $(event.relatedTarget).data('audit-id');

        $(event.currentTarget).find('input[name="audit_id"]').val(id);
    });

    document.querySelector('form[name="audit_refresh_modal"]').addEventListener('submit', event => {
        pause_submit_button(event.currentTarget);

        // Create a professional loading overlay
        const overlay = document.createElement('div');
        overlay.id = 'audit-loading-overlay';
        overlay.innerHTML = `
            <style>
                #audit-loading-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    background: var(--body-bg);
                    overflow: hidden;
                }
                #audit-loading-overlay .p-5 {
                    position: relative;
                    z-index: 10;
                }
            </style>
            
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #f8fafc; opacity: 0.95; z-index: 1; pointer-events: none; display: flex; flex-direction: column; align-items: center; width: 100%;">

                
                <!-- Mock Header -->
                <div style="display: flex; justify-content: space-between; padding: 25px 80px; align-items: center; width: 100%; max-width: 1200px;">
                    <div style="font-weight: 800; font-size: 1.4rem; color: #0f172a; display: flex; align-items: center;">SiteAnalysisTool</div>
                    <div style="display: flex; gap: 25px; font-weight: 600; font-size: 0.85rem; color: #475569; align-items: center;">
                        <span>About us</span>
                        <span>Tools</span>
                        <span>Pricing</span>
                        <span>Blog</span>
                        <span>Contact us</span>
                        <span>Dashboard</span>
                        <i class="fas fa-bell" style="font-size: 0.95rem; color: #64748b;"></i>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 25px; height: 25px; background: #e2e8f0; border-radius: 50%;"></div>
                            <span>hartest</span>
                        </div>
                    </div>
                </div>
                
                <!-- Mock Hero Section -->
                <div style="margin-top: 80px; display: flex; flex-direction: column; align-items: center; text-align: center; width: 100%; padding: 0 20px;">
                    <h1 style="font-size: 3.8rem; font-weight: 800; color: #3b82f6; margin-bottom: 15px; letter-spacing: -1px;">SEO Audits & Tools</h1>
                    <p style="font-size: 1.2rem; color: #475569; max-width: 600px; margin-bottom: 35px; line-height: 1.6;">Get your webpages audited professionally and improve your SEO in minutes.</p>
                    
                    <div style="width: 90%; max-width: 750px; height: 60px; background: #ffffff; border-radius: 1rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02); border: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; padding: 0 10px;">
                        <span style="color: #94a3b8; font-size: 0.95rem; margin-left: 15px;">https://example.com/</span>
                        <div style="display: flex; gap: 5px;">
                            <div style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #3b82f6; display: flex; align-items: center; justify-content: center;"><i class="fas fa-wrench text-primary" style="font-size: 0.9rem;"></i></div>
                            <div style="background: #3b82f6; color: #ffffff; padding: 0 20px; height: 40px; border-radius: 8px; font-weight: bold; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;"><i class="fas fa-bolt"></i> Start audit</div>
                        </div>
                    </div>
                </div>

                <!-- Step Cards Mock -->
                <div style="margin-top: 60px; display: flex; gap: 20px; width: 100%; max-width: 1000px; justify-content: center; padding: 0 20px;">
                    <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.03); flex: 1; text-align: left; max-width: 250px;">
                        <span style="background: #eff6ff; color: #3b82f6; font-weight: bold; padding: 2px 10px; border-radius: 6px; font-size: 0.75rem;">1</span>
                        <div style="font-weight: bold; margin-top: 10px; font-size: 0.85rem;">Paste your URL.</div>
                        <div style="color: #64748b; font-size: 0.75rem; margin-top: 4px;">Single page, bulk, sitemap or raw HTML.</div>
                    </div>
                    <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.03); flex: 1; text-align: left; max-width: 250px;">
                        <span style="background: #eff6ff; color: #3b82f6; font-weight: bold; padding: 2px 10px; border-radius: 6px; font-size: 0.75rem;">2</span>
                        <div style="font-weight: bold; margin-top: 10px; font-size: 0.85rem;">Run the audit.</div>
                        <div style="color: #64748b; font-size: 0.75rem; margin-top: 4px;">Get effective insights in seconds.</div>
                    </div>
                    <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.03); flex: 1; text-align: left; max-width: 250px;">
                        <span style="background: #eff6ff; color: #3b82f6; font-weight: bold; padding: 2px 10px; border-radius: 6px; font-size: 0.75rem;">3</span>
                        <div style="font-weight: bold; margin-top: 10px; font-size: 0.85rem;">Fix issues & improve.</div>
                        <div style="color: #64748b; font-size: 0.75rem; margin-top: 4px;">Get actionable tips to improve your SEO.</div>
                    </div>
                </div>
            </div>

            <!-- Deep Website Audit loading card -->
            <div class="p-5 bg-white shadow-lg border-0 text-center" style="width: 90%; max-width: 500px; border-radius: 2rem;">
                <div class="mb-4">
                    <div class="spinner-grow text-primary" role="status" style="width: 4rem; height: 4rem;"></div>
                </div>
                <h2 class="h3 font-weight-bold text-dark mb-2">Deep Website Audit</h2>
                <p class="text-muted mb-4">Please wait while we perform a comprehensive SEO analysis of your site.</p>
                
                <div class="position-relative mb-4">
                    <div class="progress shadow-sm" style="height: 35px; border-radius: 20px; background: #f0f2f5; overflow: hidden;">
                        <div class="progress-bar progress-bar-animated progress-bar-striped bg-primary" role="progressbar" style="width: 0%; transition: width 0.4s cubic-bezier(0.1, 0.7, 0.1, 1);" data-progress-bar></div>
                    </div>
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
                        <span class="font-weight-bold text-dark" style="font-size: 1.1rem;" data-progress-percentage>0%</span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-center text-primary font-weight-bold mb-0">
                    <i class="fas fa-search-location mr-2 fa-spin"></i>
                    <span data-status-text>Initializing Lighthouse Analysis...</span>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        // Simulation logic
        let percentage = 0;
        const percentage_element = overlay.querySelector('[data-progress-percentage]');
        const progress_bar = overlay.querySelector('[data-progress-bar]');
        const status_text = overlay.querySelector('[data-status-text]');

        const statuses = [
            { p: 10, t: "Fetching page source..." },
            { p: 25, t: "Analyzing DOM structure..." },
            { p: 45, t: "Evaluating Meta tags & SEO content..." },
            { p: 65, t: "Checking accessibility & performance..." },
            { p: 85, t: "Performing Google PageSpeed handshake..." },
            { p: 95, t: "Finalizing comprehensive report..." }
        ];

        const interval = setInterval(() => {
            let increment = 0;
            if(percentage < 40) increment = Math.random() * 5 + 2;
            else if(percentage < 75) increment = Math.random() * 2 + 0.5;
            else if(percentage < 95) increment = Math.random() * 0.5 + 0.1;
            else increment = 0.1;

            percentage += increment;
            if(percentage > 99) {
                percentage = 99;
                clearInterval(interval);
            }

            const rounded = Math.floor(percentage);
            percentage_element.innerText = `${rounded}%`;
            progress_bar.style.width = `${rounded}%`;

            // Update status text
            const currentStatus = statuses.filter(s => rounded >= s.p).pop();
            if(currentStatus) status_text.innerText = currentStatus.t;
        }, 600);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
