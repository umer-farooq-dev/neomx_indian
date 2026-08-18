<?php

/*
|--------------------------------------------------------------------------
| Pending schema changes
|--------------------------------------------------------------------------
|
| Run this once per environment after deploying:
|
|     php nx-schema-update.php
|
| Safe to run again — every column is checked before it is added, so nothing
| is duplicated and existing data is untouched.
|
| This is a plain script rather than a migration because the app was installed
| from a SQL dump and has no migrations table; running `artisan migrate` here
| would try to apply Laravel's stock migrations against the existing schema.
|
*/

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$changes = [
    // registration form collects a date of birth
    ['users', 'dob', fn (Blueprint $t) => $t->date('dob')->nullable()->after('mobile')],

    // a deposit now carries the plan the user picked, so the investment can be
    // opened automatically once the payment is confirmed
    ['deposits', 'plan_id', fn (Blueprint $t) => $t->unsignedInteger('plan_id')->nullable()->after('user_id')],
];

foreach ($changes as [$table, $column, $definition]) {
    if (Schema::hasColumn($table, $column)) {
        echo "OK: $table.$column already exists.\n";
        continue;
    }

    Schema::table($table, $definition);
    echo "OK: added $table.$column\n";
}

echo "\nDone. Now clear the caches:\n";
echo "  php artisan view:clear\n";
echo "  php artisan cache:clear\n";
