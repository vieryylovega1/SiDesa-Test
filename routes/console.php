<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sidesa:backup-database {--path=}', function () {
    $connection = config('database.default');
    $config = config("database.connections.{$connection}");

    if (($config['driver'] ?? null) !== 'mysql') {
        $this->error('Backup otomatis saat ini hanya mendukung koneksi MySQL/MariaDB.');
        return 1;
    }

    $database = $config['database'] ?? null;
    $username = $config['username'] ?? null;

    if (! $database || ! $username) {
        $this->error('Konfigurasi database belum lengkap. Periksa DB_DATABASE dan DB_USERNAME di .env.');
        return 1;
    }

    $directory = $this->option('path') ?: storage_path('app/backups');
    File::ensureDirectoryExists($directory);

    $filename = $database . '_' . now()->format('Ymd_His') . '.sql';
    $target = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    $command = [
        'mysqldump',
        '--host=' . ($config['host'] ?? '127.0.0.1'),
        '--port=' . ($config['port'] ?? 3306),
        '--user=' . $username,
        '--single-transaction',
        '--routines',
        '--triggers',
        $database,
    ];

    $environment = [];
    if (($config['password'] ?? '') !== '') {
        $environment['MYSQL_PWD'] = $config['password'];
    }

    $process = new Process($command, base_path(), $environment);
    $process->setTimeout(180);
    $process->run();

    if (! $process->isSuccessful()) {
        $this->error('Backup gagal. Pastikan mysqldump tersedia di PATH Laragon.');
        $this->line(trim($process->getErrorOutput()) ?: trim($process->getOutput()));
        return 1;
    }

    File::put($target, $process->getOutput());

    $this->info('Backup database berhasil dibuat:');
    $this->line($target);

    return 0;
})->purpose('Backup database SI-DESA ke file SQL');
