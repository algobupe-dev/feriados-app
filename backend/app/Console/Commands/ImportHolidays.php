<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HolidayImportService;

class ImportHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa feriados desde API Externa';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = app(HolidayImportService::class)->import();

        $this->info("✔ {$count} feriados importados correctamente.");

        return Command::SUCCESS;
    }
}
