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

class TransactionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Transaction::getAll($request);

        return $this->sendResponse($result, 'Transactions retrieved successfully.');
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
                return $this->sendError('Debit and credit accounts cannot be the same.', [], 400);
            }

            DB::beginTransaction();

            $journal = Journal::addRow($transactionRequest);
            Transaction::batchInsert($transactionRequest, $journal->id);

            DB::commit();

            return $this->sendResponse($journal, 'Transaction created successfully.');
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
            $journalTransaction = Journal::getOne($journalId);

            if ($journalTransaction == null) {
                return $this->sendError('Transaction not found.', [], 404);
            }

            DB::beginTransaction();

            //Switch sender and receiver to revert transaction
            $temp = $journalTransaction->credit_account_id;
            $journalTransaction->credit_account_id = $journalTransaction->debit_account_id;
            $journalTransaction->debit_account_id = $temp;
            $journal = Journal::addRow($journalTransaction);;

            Transaction::batchInsert($journalTransaction, $journal->id);

            DB::commit();

            return $this->sendResponse($journal, 'Transaction reverted successfully.');
        } catch (\Exception $exp) {
            DB::rollBack();
            return $this->sendError($exp->getMessage(), [], 400);
        }
    }

    public function contactTransaction(ContactTransactionRequest $contactTransactionRequest)
    {

        if ($contactTransactionRequest->credit_account_id == $contactTransactionRequest->debit_account_id) {
            return $this->sendError('Debit and credit accounts cannot be the same.', [], 400);;
        }

        $contact = Contact::getOne($contactTransactionRequest->contact_id);

        if ($contact == null) {
            return $this->sendError('Contact not found.', [], 404);
        }

        $isAccountsAreValid = TypeAccount::canProcessTransaction(
            $contactTransactionRequest->credit_account_id,
            $contactTransactionRequest->debit_account_id,
            $contact->type_id
        );

        if (!$isAccountsAreValid) {
            return $this->sendError('Accounts are not valid for this contact.', [], 404);
        }

        try {
            DB::beginTransaction();

            $journal = Journal::addRow($contactTransactionRequest);

            Transaction::batchInsert(
                $contactTransactionRequest,
                $journal->id
            );

            DB::commit();

            return $this->sendResponse($journal, 'Transaction created successfully.');
        } catch (\Exception $exp) {
            DB::rollBack();

            return $this->sendError($exp->getMessage(), [], 400);
        }
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
