<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\HolidayImportService;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        app(HolidayImportService::class)->import();
    }
}