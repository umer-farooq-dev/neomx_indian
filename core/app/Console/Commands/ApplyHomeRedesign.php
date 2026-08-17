<?php

namespace App\Console\Commands;

use App\Models\Frontend;
use App\Models\Page;
use Illuminate\Console\Command;

/**
 * The redesigned homepage renders a new set of sections and needs one extra
 * "how it works" step. Section order and CMS content live in the database, not
 * in the repo, so deploying the code alone is not enough — run this once per
 * environment after pulling. It is safe to re-run: nothing is duplicated and
 * existing wording is left alone.
 */
class ApplyHomeRedesign extends Command
{
    protected $signature = 'nx:apply-home-redesign';

    protected $description = 'Point the homepage at the redesigned sections and add the missing setup step';

    public function handle()
    {
        $template = activeTemplate();

        $page = Page::where('tempname', $template)->where('slug', '/')->first();

        if (!$page) {
            $this->error("No homepage record found for template [$template].");
            return self::FAILURE;
        }

        $sections = [
            'nx_stats',
            'nx_plans',
            'nx_how_it_works',
            'nx_referral',
            'nx_dashboard_preview',
            'nx_trust',
            'nx_about',
            'nx_faq',
        ];

        $page->secs = json_encode($sections);
        $page->save();
        $this->info('Homepage sections updated.');

        $stepKey = 'how_it_work.element';
        $steps   = Frontend::where('tempname', activeTemplateName())->where('data_keys', $stepKey)->count();

        if ($steps >= 5) {
            $this->line("Setup steps already complete ($steps found) — nothing added.");
        } else {
            $step             = new Frontend();
            $step->data_keys  = $stepKey;
            $step->tempname   = activeTemplateName();
            $step->data_values = [
                'title'       => 'Withdraw',
                'description' => 'Withdraw your earnings whenever you need them.',
            ];
            $step->save();
            $this->info('Added the missing "Withdraw" step.');
        }

        $this->newLine();
        $this->line('Done. Clear caches next: php artisan view:clear && php artisan cache:clear');

        return self::SUCCESS;
    }
}
