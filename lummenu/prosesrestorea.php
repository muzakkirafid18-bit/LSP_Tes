<?php
require_once __DIR__ . '/../lsp_koneksi.php';

$mysql = getenv('MYSQL_BIN') ?: 'C:\\xampp\\mysql\\bin\\mysql.exe';
$backupFile = __DIR__ . '/backupdata/Backup_Data.sql';

if (!is_file($mysql)) {
    http_response_code(500);
    exit('<strong>Restore gagal:</strong> mysql.exe tidak ditemukan. Periksa instalasi XAMPP atau set variabel MYSQL_BIN.');
}

if (!is_file($backupFile) || filesize($backupFile) === 0) {
    http_response_code(404);
    exit('<strong>Restore gagal:</strong> file backup tidak ditemukan atau kosong.');
}

$passwordArg = $pass === '' ? '' : ' --password=' . escapeshellarg($pass);
$command = escapeshellarg($mysql)
    . ' --host=' . escapeshellarg($host)
    . ' --user=' . escapeshellarg($user)
    . $passwordArg
    . ' ' . escapeshellarg($db)
    . ' < ' . escapeshellarg($backupFile)
    . ' 2>&1';

exec($command, $output, $exitCode);

if ($exitCode !== 0) {
    http_response_code(500);
    $detail = implode("\n", $output);
    exit('<strong>Restore gagal.</strong><pre>' . htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') . '</pre>');
}

echo '<strong>Restore berhasil diselesaikan.</strong>';
?>


