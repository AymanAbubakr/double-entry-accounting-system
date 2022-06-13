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
    public function index()
    {
        $transactions = Transaction::all()->where('deleted', 0);

        return response()->json([
            'transactions' => $transactions
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
                'amount' => $transactionRequest->credit_amount,
                'comment' => $transactionRequest->comment,
            ]);

            Transaction::insert([
                [
                    'credit_account_id' => $transactionRequest->credit_account_id,
                    'debit_account_id' => $transactionRequest->debit_account_id,
                    'credit_amount' => $transactionRequest->credit_amount,
                    'debit_amount' => 0,
                    'journal_id' => $journal->id,
                    'comment' => $transactionRequest->comment,
                ],
                [
                    'credit_account_id' => $transactionRequest->credit_account_id,
                    'debit_account_id' => $transactionRequest->debit_account_id,
                    'credit_amount' => 0,
                    'debit_amount' =>  $transactionRequest->credit_amount,
                    'journal_id' => $journal->id,
                    'comment' => $transactionRequest->comment,
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

    public function revertTransaction($id)
    {
        try {
            $transaction = Transaction::find($id);

            if ($transaction->is_null) {
                return response()->json([
                    'message' => "Transaction not found!",
                ], 404);
            }

            DB::beginTransaction();

            $journal = Journal::create([
                'credit_account_id' => $transaction->credit_account_id,
                'debit_account_id' => $transaction->debit_account_id,
                'amount' => $transaction->credit_amount,
                'comment' => $transaction->comment,
            ]);
    
            Transaction::insert([
                [
                    'credit_account_id' => $transaction->debit_account_id,
                    'debit_account_id' => $transaction->credit_account_id,
                    'credit_amount' => $transaction->credit_amount,
                    'debit_amount' => 0,
                    'journal_id' => $journal->id,
                    'comment' => $transaction->comment,
                ],
                [
                    'credit_account_id' => $transaction->debit_account_id,
                    'debit_account_id' => $transaction->credit_account_id,
                    'credit_amount' => 0,
                    'journal_id' => $journal->id,
                    'debit_amount' =>  $transaction->credit_amount,
                    'comment' => $transaction->comment,
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
