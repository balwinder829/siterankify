<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="flex flex-wrap items-center gap-2 text-xs text-slate-400 font-bold mb-4">
                <li>
                    <a href="<?= url('notification-handlers') ?>"><?= l('notification_handlers.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="text-slate-700" aria-current="page"><?= l('notification_handler_update.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-bell mr-1"></i> <?= l('notification_handler_update.header') ?></h1>

        <?= include_view(THEME_PATH . 'views/notification-handlers/notification_handler_dropdown_button.php', ['id' => $data->notification_handler->notification_handler_id, 'resource_name' => $data->notification_handler->name]) ?>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div class="space-y-4">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="space-y-1.5 mb-4">
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-signature fa-sm text-slate-500 mr-1"></i> <?= l('global.name') ?></label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->name ?>" required="required" />
                </div>

                <div class="space-y-1.5 mb-4">
                    <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-fingerprint text-slate-500 mr-1"></i> <?= l('notification_handlers.type') ?></label>
                    <select id="type" name="type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" required="required">
                        <?php $available_notification_handlers = require APP_PATH . 'includes/available_notification_handlers.php' ?>
                        <?php foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler): ?>
                            <option value="<?= $notification_handler ?>" <?= $data->notification_handler->type == $notification_handler ? 'selected="selected"' : null ?>>
                                <?= $available_notification_handlers[$notification_handler]['emoji'] ?>

                                <?= l('notification_handlers.type_' . $notification_handler) ?>

                                <?php if($this->user->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} != -1 && ($data->total_notification_handlers[$notification_handler] ?? 0) >= $this->user->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'}): ?>
                                    - <?= l('global.info_message.plan_feature_limit') ?>
                                <?php endif ?>
                            </option>

                        <?php endforeach ?>
                    </select>
                </div>

                <div class="space-y-1.5 mb-4" data-type="email">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-envelope text-slate-500 mr-1"></i> <?= l('notification_handlers.email') ?></label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->email ?? '' ?>" maxlength="512" placeholder="<?= l('global.email_placeholder') ?>" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.email_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="webhook">
                    <label for="webhook" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-satellite-dish text-slate-500 mr-1"></i> <?= l('notification_handlers.webhook') ?></label>
                    <input type="url" id="webhook" name="webhook" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->webhook ?? '' ?>" maxlength="512" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.webhook_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="slack">
                    <label for="slack" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-sm fa-slack text-slate-500 mr-1"></i> <?= l('notification_handlers.slack') ?></label>
                    <input type="url" id="slack" name="slack" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->slack ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.slack_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="discord">
                    <label for="discord" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-sm fa-discord text-slate-500 mr-1"></i> <?= l('notification_handlers.discord') ?></label>
                    <input type="url" id="discord" name="discord" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->discord ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.discord_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="microsoft_teams">
                    <label for="microsoft_teams" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-sm fa-microsoft text-slate-500 mr-1"></i> <?= l('notification_handlers.microsoft_teams') ?></label>
                    <input type="url" id="microsoft_teams" name="microsoft_teams" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->microsoft_teams ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.microsoft_teams_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="twilio">
                    <label for="twilio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-sms text-slate-500 mr-1"></i> <?= l('notification_handlers.twilio') ?></label>
                    <input type="tel" id="twilio" name="twilio" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->twilio ?? '' ?>" maxlength="32" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.twilio_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="twilio_call">
                    <label for="twilio_call" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-sms text-slate-500 mr-1"></i> <?= l('notification_handlers.twilio_call') ?></label>
                    <input type="tel" id="twilio_call" name="twilio_call" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->twilio_call ?? '' ?>" maxlength="32" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.twilio_call_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="telegram">
                    <label for="telegram" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-sm fa-telegram text-slate-500 mr-1"></i> <?= l('notification_handlers.telegram') ?></label>
                    <input type="tel" id="telegram" name="telegram" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->telegram ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.telegram_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="telegram">
                    <label for="telegram_chat_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-comment-alt text-slate-500 mr-1"></i> <?= l('notification_handlers.telegram_chat_id') ?></label>
                    <input type="tel" id="telegram_chat_id" name="telegram_chat_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->telegram_chat_id ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.telegram_chat_id_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="whatsapp">
                    <label for="whatsapp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-sm fa-whatsapp text-slate-500 mr-1"></i> <?= l('notification_handlers.whatsapp') ?></label>
                    <input type="tel" id="whatsapp" name="whatsapp" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->whatsapp ?? '' ?>" maxlength="32" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.whatsapp_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="google_chat">
                    <label for="google_chat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-sm fa-google text-slate-500 mr-1"></i> <?= l('notification_handlers.google_chat') ?></label>
                    <input type="url" id="google_chat" name="google_chat" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->google_chat ?? '' ?>" maxlength="512" required="required" />
                    <small class="text-slate-500"><?= l('notification_handlers.google_chat_help') ?></small>
                </div>

                <div class="space-y-1.5 mb-4" data-type="x">
                    <label for="x_consumer_key" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fa fa-fw fa-sm fa-key text-slate-500 mr-1"></i> <?= l('notification_handlers.x_consumer_key') ?></label>
                    <input type="text" id="x_consumer_key" name="x_consumer_key" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->x_consumer_key ?? '' ?>" maxlength="512" required="required" />
                </div>

                <div class="space-y-1.5 mb-4" data-type="x">
                    <label for="x_consumer_secret" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fa fa-fw fa-sm fa-key text-slate-500 mr-1"></i> <?= l('notification_handlers.x_consumer_secret') ?></label>
                    <input type="text" id="x_consumer_secret" name="x_consumer_secret" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->x_consumer_secret ?? '' ?>" maxlength="512" required="required" />
                </div>

                <div class="space-y-1.5 mb-4" data-type="x">
                    <label for="x_access_token" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fa fa-fw fa-sm fa-key text-slate-500 mr-1"></i> <?= l('notification_handlers.x_access_token') ?></label>
                    <input type="text" id="x_access_token" name="x_access_token" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->x_access_token ?? '' ?>" maxlength="512" required="required" />
                </div>

                <div class="space-y-1.5 mb-4" data-type="x">
                    <label for="x_access_token_secret" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fa fa-fw fa-sm fa-key text-slate-500 mr-1"></i> <?= l('notification_handlers.x_access_token_secret') ?></label>
                    <input type="text" id="x_access_token_secret" name="x_access_token_secret" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" value="<?= $data->notification_handler->settings->x_access_token_secret ?? '' ?>" maxlength="512" required="required" />
                </div>

                <?php if(settings()->internal_notifications->users_is_enabled): ?>
                    <div class="space-y-1.5 mb-4" data-type="internal_notification">
                        <small class="text-slate-500"><?= l('notification_handlers.internal_notification_help') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('push-notifications') && settings()->push_notifications->is_enabled): ?>
                    <div class="space-y-1.5 mb-4" data-type="push_subscriber_id">
                        <label for="push_subscriber_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-thumbtack text-slate-500 mr-1"></i> <?= l('notification_handlers.push_subscriber_id') ?></label>
                        <select id="push_subscriber_id" name="push_subscriber_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" required="required">
                            <?php $push_subscribers = db()->where('user_id', $this->user->user_id)->get('push_subscribers'); ?>
                            <?php foreach($push_subscribers as $push_subscriber): ?>
                                <option value="<?= $push_subscriber->push_subscriber_id ?>" <?= $data->notification_handler->settings->push_subscriber_id == $push_subscriber->push_subscriber_id ? 'selected="selected"' : null ?> <?= $this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'} != -1 && ($data->total_notification_handlers['push_subscriber_id'] ?? 0) >= $this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'} ? 'disabled="disabled"' : null ?>>
                                    <?= $push_subscriber->ip . ' - ' . l('global.device.' . $push_subscriber->device_type) . ' - ' . $push_subscriber->os_name . ' - ' . $push_subscriber->browser_name ?>

                                    <?php if($this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'} != -1 && ($data->total_notification_handlers['push_subscriber_id'] ?? 0) >= $this->user->plan_settings->{'notification_handlers_push_subscriber_id_limit'}): ?>
                                        - <?= l('global.info_message.plan_feature_limit') ?>
                                    <?php endif ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-slate-500"><?= l('notification_handlers.push_subscriber_id_help') ?></small>
                    </div>
                <?php endif ?>

                <div class="form-group custom-control custom-switch">
                    <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->notification_handler->is_enabled ? 'checked="checked"' : null?>>
                    <label class="custom-control-label" for="is_enabled"><?= l('notification_handlers.is_enabled') ?></label>
                </div>

                <button type="submit" name="test" class="w-full py-3 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 font-bold text-xs transition-all cursor-pointer mt-4 block text-center" <?= (session_get('notification_handler_test_' . ($_POST['type'] ?? '')) ?? 0) < 10 ? null : 'disabled="disabled"' ?>><?= l('notification_handlers.test') ?></button>

                <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 mt-4 block text-center"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    
type_handler('select[name="type"]', 'data-type');
    document.querySelector('select[name="type"]') && document.querySelectorAll('select[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('select[name="type"]', 'data-type'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
