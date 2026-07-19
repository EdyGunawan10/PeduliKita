<?php
require_once __DIR__.'/helpers.php';
$pageTitle='Daftar Kampanye';
$category=trim($_GET['category']??'');$q=trim($_GET['q']??'');
$where=["c.status='active'"];$params=[];
if($category!==''){$where[]='c.category=?';$params[]=$category;}
if($q!==''){$where[]='(c.title LIKE ? OR c.summary LIKE ? OR c.location LIKE ?)';$params=array_merge($params,["%$q%","%$q%","%$q%"]);}
$stmt=db()->prepare(campaign_query(implode(' AND ',$where)).' ORDER BY c.created_at DESC');$stmt->execute($params);$campaigns=$stmt->fetchAll();
$categories=db()->query("SELECT DISTINCT category FROM campaigns WHERE status='active' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
include __DIR__.'/partials/header.php';
?>
<section class="page-hero container"><span class="eyebrow">Kampanye kebaikan</span><h1>Temukan tujuan yang berarti</h1><p>Pilih kampanye yang dekat dengan kepedulian Anda dan pantau dampaknya secara transparan.</p></section>
<section class="container section top-tight">
<form class="filter-bar glass" method="get"><input type="search" name="q" placeholder="Cari kampanye atau lokasi..." value="<?=e($q)?>"><select name="category"><option value="">Semua kategori</option><?php foreach($categories as $c): ?><option <?=($category===$c?'selected':'')?>><?=e($c)?></option><?php endforeach; ?></select><button class="btn primary" type="submit">Cari</button></form>
<div class="campaign-grid"><?php foreach($campaigns as $campaign) include __DIR__.'/partials/campaign-card.php'; ?></div>
<?php if(!$campaigns): ?><div class="empty glass"><h3>Kampanye tidak ditemukan</h3><p>Ubah kata pencarian atau kategori.</p></div><?php endif; ?>
</section>
<?php include __DIR__.'/partials/footer.php'; ?>
