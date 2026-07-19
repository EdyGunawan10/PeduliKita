<?php
require_once __DIR__.'/helpers.php';
$slug=$_GET['slug']??'';$stmt=db()->prepare(campaign_query('c.slug=?'));$stmt->execute([$slug]);$campaign=$stmt->fetch();
if(!$campaign){http_response_code(404);exit('Kampanye tidak ditemukan.');}
$pageTitle=$campaign['title'];
$stmt=db()->prepare("SELECT * FROM campaign_updates WHERE campaign_id=? ORDER BY created_at DESC");$stmt->execute([$campaign['id']]);$updates=$stmt->fetchAll();
$stmt=db()->prepare("SELECT * FROM fund_usages WHERE campaign_id=? ORDER BY usage_date DESC");$stmt->execute([$campaign['id']]);$usages=$stmt->fetchAll();
$stmt=db()->prepare("SELECT * FROM donations WHERE campaign_id=? AND status='verified' ORDER BY verified_at DESC LIMIT 8");$stmt->execute([$campaign['id']]);$donors=$stmt->fetchAll();
$totalUsed=array_sum(array_column($usages,'amount'));
include __DIR__.'/partials/header.php';
?>
<section class="campaign-detail container">
  <div class="detail-main">
    <div class="detail-cover category-<?=e(strtolower($campaign['category']))?>"><?php if($campaign['cover_image']):?><img src="<?=url($campaign['cover_image'])?>" alt=""><?php else:?><span><?=category_icon($campaign['category'])?></span><?php endif;?><b><?=e($campaign['category'])?></b></div>
    <div class="glass panel detail-content"><span class="eyebrow"><?=e($campaign['category'])?> • <?=e($campaign['location'])?></span><h1><?=e($campaign['title'])?></h1><p class="lead"><?=e($campaign['summary'])?></p><div class="rich-text"><?=nl2br(e($campaign['description']))?></div><div class="beneficiary"><span>♥</span><div><small>Penerima manfaat</small><strong><?=e($campaign['beneficiary'])?></strong></div></div></div>
    <div class="glass panel"><div class="section-head"><div><span class="eyebrow">Perkembangan</span><h2>Kabar terbaru</h2></div></div><?php if($updates):?><div class="timeline"><?php foreach($updates as $u):?><article><time><?=date('d M Y',strtotime($u['created_at']))?></time><h3><?=e($u['title'])?></h3><p><?=nl2br(e($u['content']))?></p></article><?php endforeach;?></div><?php else:?><p class="muted">Belum ada kabar terbaru.</p><?php endif;?></div>
    <div class="glass panel"><span class="eyebrow">Akuntabilitas</span><h2>Laporan penggunaan dana</h2><?php if($usages):?><div class="usage-table"><?php foreach($usages as $u):?><div><div><strong><?=e($u['title'])?></strong><small><?=date('d M Y',strtotime($u['usage_date']))?> · <?=e($u['description'])?></small></div><b><?=money((float)$u['amount'])?></b></div><?php endforeach;?><div class="usage-total"><strong>Total digunakan</strong><b><?=money((float)$totalUsed)?></b></div></div><?php else:?><p class="muted">Belum ada penggunaan dana yang dicatat.</p><?php endif;?></div>
  </div>
  <aside class="detail-side">
    <div class="glass donate-card sticky"><small>Terkumpul</small><strong><?=money((float)$campaign['raised'])?></strong><span>dari target <?=money((float)$campaign['target_amount'])?></span><div class="progress large"><span style="width:<?=campaign_progress($campaign)?>%"></span></div><div class="donate-stats"><div><b><?=number_format((int)$campaign['donor_count'])?></b><small>Donatur</small></div><div><b><?=number_format(campaign_progress($campaign),0)?>%</b><small>Tercapai</small></div><div><b><?=max(0,(int)ceil((strtotime($campaign['deadline'])-time())/86400))?></b><small>Hari lagi</small></div></div><a class="btn primary wide" href="<?=url('donate.php?campaign='.$campaign['id'])?>">Donasi Sekarang</a><p class="secure-note">🔒 Data Anda diproses dengan aman.</p></div>
    <div class="glass panel mini-panel"><h3>Donatur terbaru</h3><?php foreach($donors as $d):?><div class="mini-donor"><span class="avatar small"><?=strtoupper(substr($d['anonymous']?'H':$d['donor_name'],0,1))?></span><div><strong><?=e($d['anonymous']?'Hamba Allah':$d['donor_name'])?></strong><small><?=money((float)$d['amount'])?></small></div></div><?php endforeach;?></div>
  </aside>
</section>
<?php include __DIR__.'/partials/footer.php'; ?>
