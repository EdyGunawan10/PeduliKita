<?php
require_once __DIR__.'/helpers.php';
$pageTitle='Donasi Transparan, Dampak Nyata';
$stats=db()->query("SELECT COALESCE(SUM(CASE WHEN status='verified' THEN amount END),0) total, COUNT(DISTINCT CASE WHEN status='verified' THEN id END) donations, COUNT(DISTINCT CASE WHEN status='verified' THEN campaign_id END) funded FROM donations")->fetch();
$campaigns=db()->query(campaign_query("c.status='active'")." ORDER BY c.created_at DESC LIMIT 3")->fetchAll();
$recent=db()->query("SELECT d.*,c.title campaign_title FROM donations d JOIN campaigns c ON c.id=d.campaign_id WHERE d.status='verified' ORDER BY d.verified_at DESC LIMIT 6")->fetchAll();
include __DIR__.'/partials/header.php';
?>
<section class="hero container">
  <div class="hero-copy">
    <span class="eyebrow">Platform donasi transparan</span>
    <h1>Kebaikan kecil.<br><span>Dampak besar.</span></h1>
    <p>Temukan kampanye tepercaya, kirim bantuan dengan mudah, dan pantau penggunaan dana secara terbuka.</p>
    <div class="hero-actions"><a class="btn primary" href="<?=url('campaigns.php')?>">Mulai Berdonasi</a><a class="btn ghost" href="<?=url('transparency.php')?>">Lihat Transparansi</a></div>
    <div class="trust-row"><span>✓ Verifikasi donasi</span><span>✓ Laporan penggunaan</span><span>✓ Bukti donasi</span></div>
  </div>
  <div class="hero-visual glass">
    <div class="orbital-card main-impact"><small>Dana tersalurkan</small><strong><?=compact_money((float)$stats['total'])?></strong><div class="mini-bars"><i style="height:36%"></i><i style="height:55%"></i><i style="height:46%"></i><i style="height:78%"></i><i style="height:92%"></i></div></div>
    <div class="orbital-card donor-bubble"><span class="avatar">♥</span><div><small>Donasi terbaru</small><strong><?=isset($recent[0])?money((float)$recent[0]['amount']):'Rp0'?></strong></div></div>
    <div class="orbital-card verified-bubble"><span>✓</span> Transparan & terverifikasi</div>
  </div>
</section>
<section class="stats-strip container glass">
  <div><strong><?=compact_money((float)$stats['total'])?></strong><span>Total donasi terverifikasi</span></div>
  <div><strong><?=number_format((int)$stats['donations'])?></strong><span>Transaksi kebaikan</span></div>
  <div><strong><?=number_format((int)$stats['funded'])?></strong><span>Kampanye didukung</span></div>
  <div><strong>100%</strong><span>Laporan dapat dipantau</span></div>
</section>
<section class="section container">
  <div class="section-head"><div><span class="eyebrow">Kampanye pilihan</span><h2>Bersama, kita bantu mereka</h2></div><a class="text-link" href="<?=url('campaigns.php')?>">Lihat semua →</a></div>
  <div class="campaign-grid"><?php foreach($campaigns as $campaign) include __DIR__.'/partials/campaign-card.php'; ?></div>
</section>
<section class="section container two-col">
  <div class="glass panel"><span class="eyebrow">Alur sederhana</span><h2>Tiga langkah menuju dampak</h2><div class="steps"><div><b>01</b><h3>Pilih kampanye</h3><p>Pelajari tujuan, penerima manfaat, target, dan perkembangannya.</p></div><div><b>02</b><h3>Kirim donasi</h3><p>Isi data, pilih metode pembayaran, dan unggah bukti transfer.</p></div><div><b>03</b><h3>Pantau hasilnya</h3><p>Lihat verifikasi, berita perkembangan, dan laporan penggunaan dana.</p></div></div></div>
  <div class="glass panel recent-panel"><span class="eyebrow">Aktivitas terbaru</span><h2>Kebaikan yang terus bergerak</h2><div class="activity-list"><?php foreach($recent as $r): ?><div><span class="avatar small"><?=strtoupper(substr($r['anonymous']?'H':$r['donor_name'],0,1))?></span><p><strong><?=e($r['anonymous']?'Hamba Allah':$r['donor_name'])?></strong> berdonasi <?=money((float)$r['amount'])?><small><?=e($r['campaign_title'])?></small></p></div><?php endforeach; ?></div></div>
</section>
<?php include __DIR__.'/partials/footer.php'; ?>
