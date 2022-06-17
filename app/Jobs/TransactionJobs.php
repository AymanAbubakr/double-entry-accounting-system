<?php

namespace App\Jobs;

use App\Models\AccountBalance;
use App\Models\Journal;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TransactionJobs  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */



    private $request;
    private $transactionType;
    public function __construct($request)
    {
        $this->request = (object)$request;
        $this->transactionType = $request['transactionType'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            if ($this->transactionType == 'store') {
                $journal = Journal::addRow($this->request);

                AccountBalance::refelectAccountBalance($this->request);

                Transaction::batchInsert($this->request, $journal->id);

                DB::commit();
            } else if ($this->transactionType == 'revert') {

                //Switch sender and receiver to revert transaction
                $temp = $this->request->credit_account_id;
                $this->request->credit_account_id = $this->request->debit_account_id;
                $this->request->debit_account_id = $temp;
                $this->request->reference_id =  $this->request->id;

                $journal = Journal::addRow($this->request);

                AccountBalance::refelectAccountBalance($this->request);

                Transaction::batchInsert($this->request, $journal->id);

                DB::commit();
            } else if ($this->transactionType == 'contact') {
                $journal = Journal::addRow($this->request);

                AccountBalance::refelectAccountBalance($this->request);

                Transaction::batchInsert(
                    $this->request,
                    $journal->id
                );
                DB::commit();
            }
        } catch (\Exception $exp) {
            printf("Error: " . $exp->getMessage() . "\n");
            DB::rollBack();
        }
    }
}
