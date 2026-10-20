<?php

/**
 * reset_passwords.php
 *
 * Utility script to forcibly reset ALL user passwords to "password123".
 * Intended for local development and testing ONLY.
 *
 * Usage (from project root):
 *   php reset_passwords.php
 *
 * WARNING: Never run this on a production server.
 */

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// -------------------------------------------------------
$newPassword = 'password123';
// -------------------------------------------------------

echo "\n==============================================\n";
echo "  CVHRDC — Bulk Password Reset Utility\n";
echo "==============================================\n\n";

$users = User::with('roles')->get();

if ($users->isEmpty()) {
    echo "  No users found in the database.\n\n";
    exit(0);
}

$count = 0;

foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->join(', ');
    $roleLabel = $roles ?: 'No Role';

    $user->password = Hash::make($newPassword);
    $user->save();

    echo "  ✔  [{$roleLabel}] {$user->name} ({$user->email})\n";
    $count++;
}

echo "\n----------------------------------------------\n";
echo "  Done. {$count} user(s) updated.\n";
echo "  New password: {$newPassword}\n";
echo "----------------------------------------------\n\n";
