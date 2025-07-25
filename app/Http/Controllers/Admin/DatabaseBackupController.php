<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatabaseBackupSetting;
use Carbon\Carbon;

class DatabaseBackupController extends Controller
{
    public function show()
    {
        $settings = DatabaseBackupSetting::firstOrCreate([], [
            'frequency' => 5,
            'last_backup' => null,
        ]);
        $backupDir = storage_path('app/db-backups');
        $backups = [];
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $backups[] = [
                        'name' => $file,
                        'path' => $backupDir . DIRECTORY_SEPARATOR . $file
                    ];
                }
            }
        }
        return view('admin.settings.partials.database_backup', compact('settings', 'backups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'frequency' => 'required|integer|min:1',
        ]);
        $settings = DatabaseBackupSetting::first();
        $settings->frequency = $request->frequency;
        $settings->save();
        return redirect()->back()->with('success', 'Backup settings updated.');
    }

    public function performBackup()
    {
        $dbHost = config('database.connections.mysql.host');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbName = config('database.connections.mysql.database');
        $backupDir = storage_path('app/db-backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $fileName = 'backup_' . date('Ymd_His') . '.sql';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;
        $command = "mysqldump --host={$dbHost} --user={$dbUser} --password={$dbPass} {$dbName} > \"{$filePath}\"";
        $output = null;
        $result = null;
        exec($command, $output, $result);
        $settings = DatabaseBackupSetting::first();
        if (!$settings) {
            $settings = DatabaseBackupSetting::create([
                'frequency' => 5,
                'last_backup' => Carbon::now(),
            ]);
        } else {
            $settings->last_backup = Carbon::now();
            $settings->save();
        }
        if ($result === 0) {
            return redirect()->back()->with('success', 'Database backup performed successfully.');
        } else {
            return redirect()->back()->with('error', 'Database backup failed. Please check your mysqldump configuration.');
        }
    }

    public function download($file)
    {
        $backupDir = storage_path('app/db-backups');
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $file;
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back()->with('error', 'Backup file not found.');
        }
    }
}
