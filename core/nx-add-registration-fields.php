<?php

/*
|--------------------------------------------------------------------------
| One-time schema change for the expanded registration form
|--------------------------------------------------------------------------
|
| The registration form now collects a date of birth, which the users table
| has no column for. Run this once per environment after deploying:
|
|     php nx-add-registration-fields.php
|
| Safe to run again — it checks before adding.
|
| This is a plain script rather than a migration because the app was installed
| from a SQL dump and has no migrations table; running `artisan migrate` here
| would try to apply Laravel's stock migrations against the existing schema.
|
*/

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasColumn('users', 'dob')) {
    echo "OK: users.dob already exists, nothing to do.\n";
} else {
    Schema::table('users', function (Blueprint $table) {
        $table->date('dob')->nullable()->after('mobile');
    });
    echo "OK: added users.dob\n";
}

echo "\nDone. Now clear the caches:\n";
echo "  php artisan view:clear\n";
echo "  php artisan cache:clear\n";
