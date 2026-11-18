<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-2 font-sans space-y-5">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
            <a href="<?= url() ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('index.breadcrumb') ?></a>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="<?= url('tools') ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('tools.breadcrumb') ?></a>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-900 font-bold truncate"><?= l('tools.' . \Altum\Router::$method . '.name') ?></span>
        </div>
    <?php endif ?>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-1">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight m-0"><?= l('tools.dns_lookup.name') ?></h1>
            <p class="text-xs text-slate-500 font-normal m-0 leading-relaxed"><?= l('tools.dns_lookup.description') ?></p>
        </div>

        <?= $this->views['ratings'] ?>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
        <div>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="space-y-1.5"><label for="host" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('tools.dns_lookup.host') ?></label>
                    <input type="text" id="host" name="host" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('host') ? 'is-invalid' : null ?>" value="<?= $data->values['host'] ?>" placeholder="<?= l('global.host_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('host') ?>
                </div>

                <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer mt-4"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-8 w-8 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-poll-h text-blue-600 text-xs"></i>
                </div>
                <h2 class="text-base font-extrabold text-slate-900 m-0">Results</h2>
            </div>

            <?php if(isset($data->result['A'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('a')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-a-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.ip') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['A'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ip'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['AAAA'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('aaaa')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-aaaa-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.target') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['AAAA'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ipv6'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['CNAME'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('cname')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-cname-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.target') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['CNAME'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['target'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['MX'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('mx')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-mx-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.priority') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.target') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['MX'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['pri'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['target'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['NS'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('ns')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-ns-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ns') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['NS'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['target'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['TXT'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('txt')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-txt-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.entries') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['TXT'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['txt'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['SOA'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('soa')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-soa-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.mname') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.rname') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.serial') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.refresh') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.retry') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.expire') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.minimum_ttl') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['SOA'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['mname'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['rname'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['serial'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['refresh'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['retry'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['expire'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['minimum-ttl'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['CAA'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('caa')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-caa-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                            <tr>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('global.type') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.host') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.flags') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.tag') ?></th>
                                <th class="bg-slate-50 p-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200"><?= l('tools.dns_lookup.result.value') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['CAA'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['flags'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['tag'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['value'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

        </div>
    <?php endif ?>

    <?php if(settings()->tools->last_submissions_is_enabled && isset($data->tools_usage[\Altum\Router::$method]) && !empty((array) $data->tools_usage[\Altum\Router::$method]->data)): ?>
        <div class="mt-8"><h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2"><i class="fas fa-history text-slate-400"></i><i class="fas fa-fw fa-sm fa-plus text-primary mr-1"></i> <?= l('tools.last_submissions') ?></h2><div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                <div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach((array) $data->tools_usage[\Altum\Router::$method]->data as $key => $value): ?>
                            <div>
                                <div class="truncate text-xs font-semibold text-slate-700 py-1.5">
                                    <a href="<?= url('tools/' . str_replace('_', '-', \Altum\Router::$method) . '?' . http_build_query((array) $value)) ?>" onclick="this.href += '&submit=1<?= \Altum\Csrf::get_url_query() ?>'">
                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($value->host) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />

                                        <?= $value->host ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                </div>
            </div>
        </div>

        <?php endif ?>

    <?php require_once THEME_PATH . 'views/tools/js_dynamic_url_processor.php' ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

