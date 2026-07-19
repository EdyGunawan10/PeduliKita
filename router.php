<?php
$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ?: '/';
if(str_starts_with($path,'/data/') || str_starts_with($path,'/uploads/proofs/') || str_contains($path,'/.')){
    http_response_code(403);
    exit('Forbidden');
}
$file=__DIR__.$path;
if($path!=='/' && is_file($file)) return false;
if($path==='/') require __DIR__.'/index.php';
else { http_response_code(404); echo 'Halaman tidak ditemukan.'; }
