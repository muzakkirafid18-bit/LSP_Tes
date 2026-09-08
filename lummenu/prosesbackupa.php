<?php
require_once __DIR__ . '/../lsp_koneksi.php';

$mysqldump = getenv('MYSQLDUMP_BIN') ?: 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
$backupDir = __DIR__ . '/backupdata';
$backupFile = $backupDir . '/Backup_Data.sql';

if (!is_file($mysqldump)) {
    http_response_code(500);
    exit('<strong>Backup gagal:</strong> mysqldump tidak ditemukan. Periksa instalasi XAMPP atau set variabel MYSQLDUMP_BIN.');
}

if (!is_dir($backupDir) && !mkdir($backupDir, 0755, true) && !is_dir($backupDir)) {
    http_response_code(500);
    exit('<strong>Backup gagal:</strong> folder backupdata tidak dapat dibuat.');
}

$passwordArg = $pass === '' ? '' : ' --password=' . escapeshellarg($pass);
$command = escapeshellarg($mysqldump)
    . ' --host=' . escapeshellarg($host)
    . ' --user=' . escapeshellarg($user)
    . $passwordArg
    . ' --single-transaction --routines --events --add-drop-table '
    . escapeshellarg($db)
    . ' > ' . escapeshellarg($backupFile)
    . ' 2>&1';

exec($command, $output, $exitCode);

if ($exitCode !== 0 || !is_file($backupFile) || filesize($backupFile) === 0) {
    http_response_code(500);
    $detail = implode("\n", $output);
    exit('<strong>Backup gagal.</strong><pre>' . htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') . '</pre>');
}

echo '<strong>Backup berhasil dibuat:</strong> ' . htmlspecialchars(basename($backupFile), ENT_QUOTES, 'UTF-8');
?>


