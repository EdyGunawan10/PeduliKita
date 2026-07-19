<?php
require_once dirname(__DIR__).'/helpers.php';
require_admin();
$id=(int)($_GET['id']??0);
$stmt=db()->prepare('SELECT proof_file FROM donations WHERE id=?');
$stmt->execute([$id]);
$row=$stmt->fetch();
if(!$row || !$row['proof_file']){http_response_code(404);exit('Bukti tidak ditemukan.');}
$base=realpath(BASE_PATH.'/uploads/proofs');
$file=realpath(BASE_PATH.'/'.$row['proof_file']);
if(!$base || !$file || !str_starts_with($file,$base.DIRECTORY_SEPARATOR) || !is_file($file)){http_response_code(404);exit('Bukti tidak ditemukan.');}
$mime=(new finfo(FILEINFO_MIME_TYPE))->file($file) ?: 'application/octet-stream';
header('Content-Type: '.$mime);
header('Content-Length: '.filesize($file));
header('Content-Disposition: inline; filename="'.basename($file).'"');
header('X-Content-Type-Options: nosniff');
readfile($file);
exit;
