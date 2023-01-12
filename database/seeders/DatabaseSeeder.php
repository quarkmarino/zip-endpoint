<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $sqlFileContents = file_get_contents(database_path('backup/zip_codes_api-202301120639.sql'));

        DB::unprepared($sqlFileContents);
    }
}
