<?php
require_once __DIR__.'/helpers.php';
header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="laporan-transparansi-'.date('Y-m-d').'.csv"');
$out=fopen('php://output','w');fputs($out,"\xEF\xBB\xBF");fputcsv($out,['Tanggal','Kampanye','Penggunaan','Deskripsi','Nominal']);
$q=db()->query("SELECT f.*,c.title campaign_title FROM fund_usages f JOIN campaigns c ON c.id=f.campaign_id ORDER BY f.usage_date DESC");foreach($q as $r)fputcsv($out,[$r['usage_date'],$r['campaign_title'],$r['title'],$r['description'],$r['amount']]);fclose($out);exit;
