<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\TypeAccount;

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
            'status' => true,
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
                    'status' => false,
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
                'status' => true,
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
                    'status' => false,
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
                'status' => true,
                'message' => "Transaction Reverted successfully!",
            ], 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response([
                'status' => false,
                'message' => $exp->getMessage(),
            ], 400);
        }
    }

    public function contactTransaction(ContactTransactionRequest $contactTransactionRequest)
    {

        if ($contactTransactionRequest->credit_account_id == $contactTransactionRequest->debit_account_id) {
            return response()->json([
                'status' => false,
                'message' => "Credit and Debit account cannot be same!",
            ], 400);
        }

        $contact = Contact::where([
            ['id', $contactTransactionRequest->contact_id],
            ['deleted', 0]
        ])->first();

        if ($contact == null) {
            return response()->json([
                'status' => false,
                'message' => "Contact not found!",
            ], 404);
        }

        $typeAccounts = TypeAccount::whereIn(
            'id',
            [$contactTransactionRequest->credit_account_id, $contactTransactionRequest->debit_account_id]
        )->where(
            [
                ['deleted', 0],
                ['type_id', $contact->type_id],
            ]
        )->get();

        $isCreditAccountFound = false;
        $isDebitAccountFound = false;


        foreach ($typeAccounts as $typeAccount) {
            if ($typeAccount->account_id == $contactTransactionRequest->credit_account_id) {
                $isCreditAccountFound = true;
            } else if ($typeAccount->account_id == $contactTransactionRequest->debit_account_id) {
                $isDebitAccountFound = true;
            }
        }

        if (!$isCreditAccountFound || !$isDebitAccountFound) {
            return response()->json([
                'status' => false,
                'message' => "Credit or Debit account not found for this contact!",
            ], 404);
        }

        try {
            DB::beginTransaction();

            $journal = Journal::create([
                'credit_account_id' => $contactTransactionRequest->credit_account_id,
                'debit_account_id' => $contactTransactionRequest->debit_account_id,
                'amount' => $contactTransactionRequest->amount,
                'comment' => $contactTransactionRequest->comment,
                'contact_id' => $contactTransactionRequest->contact_id,
            ]);

            Transaction::insert([
                [
                    'credit_account_id' => $contactTransactionRequest->credit_account_id,
                    'debit_account_id' => $contactTransactionRequest->debit_account_id,
                    'amount' => $contactTransactionRequest->amount,
                    'transaction_type' => 'credit',
                    'journal_id' => $journal->id,
                ],
                [
                    'credit_account_id' => $contactTransactionRequest->credit_account_id,
                    'debit_account_id' => $contactTransactionRequest->debit_account_id,
                    'amount' => $contactTransactionRequest->amount,
                    'transaction_type' => 'debit',
                    'journal_id' => $journal->id,
                ],
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Transaction Created successfully!",
            ], 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response([
                'status' => false,
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
