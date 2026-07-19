<?php
require_once dirname(__DIR__) . '/helpers.php';
$pageTitle=$pageTitle??APP_NAME;
$bodyClass=$bodyClass??'';
$flashes=get_flashes();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#0b1020">
<title><?=e($pageTitle)?> — <?=APP_NAME?></title>
<link rel="stylesheet" href="<?=url('assets/css/style.css')?>">
</head>
<body class="<?=e($bodyClass)?>">
<div class="ambient ambient-a"></div><div class="ambient ambient-b"></div>
<header class="site-header glass">
  <a class="brand" href="<?=url('index.php')?>"><span class="brand-mark">P</span><span>PeduliKita</span></a>
  <button class="nav-toggle" type="button" aria-label="Menu" data-nav-toggle>☰</button>
  <nav class="main-nav" data-nav>
    <a href="<?=url('index.php')?>">Beranda</a>
    <a href="<?=url('campaigns.php')?>">Kampanye</a>
    <a href="<?=url('transparency.php')?>">Transparansi</a>
    <?php if(is_admin()): ?><a href="<?=url('admin/index.php')?>">Admin</a><?php endif; ?>
    <?php if(logged_in()): ?>
      <a href="<?=url('dashboard.php')?>">Donasi Saya</a>
      <a class="nav-cta" href="<?=url('logout.php')?>">Keluar</a>
    <?php else: ?>
      <a href="<?=url('login.php')?>">Masuk</a>
      <a class="nav-cta" href="<?=url('register.php')?>">Daftar</a>
    <?php endif; ?>
  </nav>
</header>
<?php if($flashes): ?><div class="flash-stack"><?php foreach($flashes as $f): ?><div class="flash <?=e($f['type'])?> glass"><?=e($f['message'])?></div><?php endforeach; ?></div><?php endif; ?>
<main>
