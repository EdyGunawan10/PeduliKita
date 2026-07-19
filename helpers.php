<?php

declare(strict_types=1);
require_once __DIR__ . '/config.php';

function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function url(string $path=''): string {
    $script = str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (str_ends_with($script, '/admin')) $script = dirname($script);
    $script = rtrim($script, '/.');
    return ($script ?: '') . '/' . ltrim($path, '/');
}
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function money(float|int|string $amount): string { return 'Rp' . number_format((float)$amount, 0, ',', '.'); }
function compact_money(float $n): string { if($n>=1_000_000_000)return 'Rp'.number_format($n/1_000_000_000,1,',','.').' M'; if($n>=1_000_000)return 'Rp'.number_format($n/1_000_000,1,',','.').' jt'; if($n>=1_000)return 'Rp'.number_format($n/1_000,0,',','.').' rb'; return money($n); }
function csrf_token(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void { if(!hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')){http_response_code(419);exit('Sesi formulir tidak valid. Muat ulang halaman.');} }
function flash(string $type, string $message): void { $_SESSION['flash'][]=['type'=>$type,'message'=>$message]; }
function get_flashes(): array { $f=$_SESSION['flash']??[]; unset($_SESSION['flash']); return $f; }
function old(string $key, string $default=''): string { return e($_POST[$key]??$default); }
function user(): ?array { return $_SESSION['user']??null; }
function logged_in(): bool { return user()!==null; }
function is_admin(): bool { return (user()['role']??'')==='admin'; }
function require_login(): void { if(!logged_in()){flash('error','Silakan masuk terlebih dahulu.');redirect('login.php');} }
function require_admin(): void { if(!is_admin()){http_response_code(403);exit('Akses ditolak.');} }
function slugify(string $text): string { $text=strtolower(trim($text)); $text=preg_replace('/[^a-z0-9]+/','-',$text); return trim($text,'-') ?: 'kampanye-'.time(); }
function receipt_code(): string { return 'PK-'.date('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)),0,8)); }
function campaign_progress(array $campaign): float {
    $raised=(float)($campaign['raised']??0); $target=max(1,(float)$campaign['target_amount']); return min(100,($raised/$target)*100);
}
function upload_file(string $field, string $folder, array $allowedMime, bool $required=false): ?string {
    if(empty($_FILES[$field]) || $_FILES[$field]['error']===UPLOAD_ERR_NO_FILE){ if($required) throw new RuntimeException('File wajib diunggah.'); return null; }
    $file=$_FILES[$field];
    if($file['error']!==UPLOAD_ERR_OK) throw new RuntimeException('Upload file gagal.');
    if($file['size']>MAX_UPLOAD_SIZE) throw new RuntimeException('Ukuran file maksimal 3 MB.');
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if(!in_array($mime,$allowedMime,true)) throw new RuntimeException('Format file tidak didukung.');
    $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'][$mime]??'bin';
    $dir=BASE_PATH.'/uploads/'.$folder; if(!is_dir($dir)) mkdir($dir,0775,true);
    $name=date('YmdHis').'-'.bin2hex(random_bytes(6)).'.'.$ext;
    if(!move_uploaded_file($file['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('File tidak dapat disimpan.');
    return 'uploads/'.$folder.'/'.$name;
}
function campaign_query(string $where='1=1'): string { return "SELECT c.*, COALESCE(SUM(CASE WHEN d.status='verified' THEN d.amount ELSE 0 END),0) raised, COUNT(DISTINCT CASE WHEN d.status='verified' THEN d.id END) donor_count FROM campaigns c LEFT JOIN donations d ON d.campaign_id=c.id WHERE $where GROUP BY c.id"; }
function category_icon(string $category): string { return match(strtolower($category)){ 'pendidikan'=>'📚','kesehatan'=>'✚','lingkungan'=>'💧','kemanusiaan'=>'♥',default=>'◆'}; }
