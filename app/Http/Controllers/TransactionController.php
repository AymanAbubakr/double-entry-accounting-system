<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = [];

        if ($request->query('type') == 'single') {
            $result = Journal::all()->where('deleted', 0);
        } else {
            $result = Transaction::all()->where('deleted', 0);
        }


        return response()->json([
            'transactions' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $transactionRequest)
    {
        try {
            if ($transactionRequest->credit_account_id == $transactionRequest->debit_account_id) {
                return response()->json([
                    'message' => "Credit and Debit account cannot be same!",
                ], 400);
            }

            DB::beginTransaction();

            $journal = Journal::create([
                'credit_account_id' => $transactionRequest->credit_account_id,
                'debit_account_id' => $transactionRequest->debit_account_id,
                'amount' => $transactionRequest->amount,
                'comment' => $transactionRequest->comment,
            ]);

            Transaction::insert([
                [
                    'credit_account_id' => $transactionRequest->credit_account_id,
                    'debit_account_id' => $transactionRequest->debit_account_id,
                    'amount' => $transactionRequest->amount,
                    'transaction_type' => 'credit',
                    'journal_id' => $journal->id,
                ],
                [
                    'credit_account_id' => $transactionRequest->credit_account_id,
                    'debit_account_id' => $transactionRequest->debit_account_id,
                    'amount' => $transactionRequest->amount,
                    'transaction_type' => 'debit',
                    'journal_id' => $journal->id,
                ],
            ]);

            DB::commit();


            return response()->json([
                'message' => "Transaction Created successfully!",
            ], 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response([
                'message' => $exp->getMessage(),
            ], 400);
        }
    }

    public function revertTransaction($journalId)
    {

        try {
            $journalTransaction = Journal::where([
                ['id', $journalId],
                ['deleted', 0]
            ])->first();

            if ($journalTransaction == null) {
                return response()->json([
                    'message' => "Transaction not found!",
                ], 404);
            }

            DB::beginTransaction();

            $journal = Journal::create([
                'credit_account_id' => $journalTransaction->credit_account_id,
                'debit_account_id' => $journalTransaction->debit_account_id,
                'amount' => $journalTransaction->amount,
                'comment' => $journalTransaction->comment,
                'reference_id' => $journalId,
            ]);

            Transaction::insert([
                [
                    'credit_account_id' => $journalTransaction->debit_account_id,
                    'debit_account_id' => $journalTransaction->credit_account_id,
                    'amount' => $journalTransaction->amount,
                    'transaction_type' => 'credit',
                    'journal_id' => $journal->id,
                ],
                [
                    'credit_account_id' => $journalTransaction->debit_account_id,
                    'debit_account_id' => $journalTransaction->credit_account_id,
                    'amount' => $journalTransaction->amount,
                    'transaction_type' => 'debit',
                    'journal_id' => $journal->id,
                ],
            ]);

            DB::commit();

            return response()->json([
                'message' => "Transaction Reverted successfully!",
            ], 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response([
                'message' => $exp->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
