<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;

class UpdateTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::all();
        foreach ($transactions as $transaction) {
            if ($transaction->isExpired()) {
                $transaction->expire();
            }
        }
    }
}
