<?php defined('ALTUMCODE') || die();
$size = $data->size ?? 'md';
$is_dark = $data->is_dark ?? false;

// Height map for optimal proportions across various UI locations:
// sm: app_menu, mobile headers (compact 26px height)
// md: main site header, sidebar, footer (balanced 32px height)
// lg: auth/login pages, splash screens (prominent 40px height)
$heights = [
    'sm' => 'h-[26px] max-h-[26px]',
    'md' => 'h-[32px] max-h-[32px]',
    'lg' => 'h-[40px] max-h-[40px]',
];

$height_class = $heights[$size] ?? $heights['md'];
$logo_img = $is_dark ? 'logo_dark.png' : 'logo.png';
$logo_url = ASSETS_FULL_URL . 'images/' . $logo_img . '?v=' . PRODUCT_CODE;
?>

<div class="inline-flex items-center select-none transition-transform duration-150 group-hover:scale-[1.02]">
    <img 
        src="<?= $logo_url ?>" 
        alt="<?= settings()->main->title ?? 'SiteRankify' ?>" 
        class="<?= $height_class ?> w-auto object-contain block" 
        loading="eager"
    />
</div>
