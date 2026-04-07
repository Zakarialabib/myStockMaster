<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Livewire\Installation\StepManager;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallDoctorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:doctor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs preflight checks to diagnose installation environment issues.';

    /** Execute the console command. */
    public function handle(): int
    {
        $this->info('========================================');
        $this->info('   MyStockMaster Installation Doctor    ');
        $this->info("========================================\n");

        $stepManager = new StepManager;
        $stepManager->runPreflightChecks();

        $errors = 0;

        $this->info('System Requirements:');

        foreach ($stepManager->preflightResults as $check) {
            $status = $check['passed'] ? '<info>PASS</info>' : '<error>FAIL</error>';
            $hint = isset($check['hint']) && ! $check['passed'] ? sprintf(' (%s)', $check['hint']) : '';

            // Format output with padding for better readability
            $label = str_pad((string) $check['label'], 30, '.');
            $this->line(sprintf('  %s %s%s', $label, $status, $hint));

            if (! $check['passed']) {
                $errors++;
            }
        }

        $this->line('');
        $this->info('Database Connection:');

        try {
            DB::connection()->getPdo();
            $this->line('  ' . str_pad('Configured Connection', 30, '.') . ' <info>PASS</info>');
        } catch (Exception $exception) {
            $this->line('  ' . str_pad('Configured Connection', 30, '.') . ' <error>FAIL</error>');
            $this->error('  Exception: ' . $exception->getMessage());
            $errors++;
        }

        $this->line("\n========================================");

        if ($errors === 0) {
            $this->info('Your environment is ready for installation!');
        } else {
            $this->error(sprintf('Found %d issue(s) that need to be resolved before installing.', $errors));
            $this->line('Please fix these issues and run `php artisan install:doctor` again.');
        }

        $this->line('========================================');

        return $errors === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
