<?php

namespace App\Console\Commands;

use App\Imports\CodigosPostalesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Excel;

class CodigosPostalesImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:codigos_postales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->title('Starting "Codigos Postales" import @ '.Carbon::now()->toDateTimeString());

        (new CodigosPostalesImport)
            ->withOutput($this->output)
            ->import('CPdescarga.txt', 'assets', Excel::CSV);

        $this->output->success('Import successful!, finished @ '.Carbon::now()->toDateTimeString());
    }
}
