<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\File;

class FileFeeCommand extends Command
{
    protected $signature = 'app:filefee {file_name}';

    protected $description = 'Calculate fees for transactions from a file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $fileName = $this->argument('file_name');

        if (!File::exists($fileName)) {
            $this->error("File not found: $fileName");
            return;
        }

        $lines = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $transactionData = json_decode($line, true);
            $transaction = new Transaction($transactionData);
            $transaction->calculateFee();

            $this->info($transaction->fee);
        }
    }
}
