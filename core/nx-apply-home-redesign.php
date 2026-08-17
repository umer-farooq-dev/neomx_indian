<?php

/*
|--------------------------------------------------------------------------
| One-time homepage redesign setup
|--------------------------------------------------------------------------
|
| Which sections the homepage renders, and the CMS copy inside them, live in
| the database rather than the repo — so pulling the code is not enough. Run
| this once per environment after deploying:
|
|     php nx-apply-home-redesign.php
|
| It is safe to run again; nothing is duplicated and existing wording is kept.
|
| This is a plain script rather than an artisan command on purpose: a freshly
| added command class is invisible to an optimised composer classmap, which is
| how the shared-hosting copy is installed.
|
*/

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Frontend;
use App\Models\Page;

$template     = activeTemplate();
$templateName = activeTemplateName();

// 1. Point the homepage at the redesigned sections.
$page = Page::where('tempname', $template)->where('slug', '/')->first();

if (!$page) {
    echo "ERROR: no homepage record found for template [$template].\n";
    exit(1);
}

$page->secs = json_encode([
    'nx_stats',
    'nx_plans',
    'nx_how_it_works',
    'nx_referral',
    'nx_dashboard_preview',
    'nx_trust',
    'nx_about',
    'nx_faq',
]);
$page->save();

echo "OK: homepage sections updated.\n";

// 2. The layout shows five setup steps; stock content ships with four.
$stepKey = 'how_it_work.element';
$steps   = Frontend::where('tempname', $templateName)->where('data_keys', $stepKey)->count();

if ($steps >= 5) {
    echo "OK: setup steps already complete ($steps found), nothing added.\n";
} else {
    $step              = new Frontend();
    $step->data_keys   = $stepKey;
    $step->tempname    = $templateName;
    $step->data_values = [
        'title'       => 'Withdraw',
        'description' => 'Withdraw your earnings whenever you need them.',
    ];
    $step->save();
    echo "OK: added the missing \"Withdraw\" step.\n";
}

echo "\nDone. Now clear the caches:\n";
echo "  php artisan view:clear\n";
echo "  php artisan cache:clear\n";
