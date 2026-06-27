<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | <?= e((string) config('app.name', 'MetaMyKad')) ?></title>
    <link rel="icon" type="image/png" href="<?= e(asset('images/favicon.png')) ?>">
    <meta name="csrf-token" content="<?= htmlspecialchars(\MetaMyKad\Core\CSRF::token(), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/reset.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/tokens.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
    <!-- Component parts (split from components.css) -->
    <link rel="stylesheet" href="<?= e(asset('css/parts/animations.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/sidebar.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/nav.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/header.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/cards.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/buttons.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/forms.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/inputs.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/forms-edit.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/custom-select.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/custom-file.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/upload.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/tags.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/tables.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/grids.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/file-cards.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/file-legacy.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/media-player.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/modal-badge.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/modal-student.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/modal-file.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/toast.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/spinner.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/login.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/register.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/home-portal.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/home-topnav.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/home-hero.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/home-features.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/utilities.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/responsive-md.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/parts/responsive-sm.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/utilities.css')) ?>">
</head>
<body>
