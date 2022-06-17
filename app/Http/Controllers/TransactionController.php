<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\AccountBalance;
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

            AccountBalance::refelectAccountBalance($transactionRequest);

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
            $journalTransaction->reference_id = $journalId;

            $journal = Journal::addRow($journalTransaction);

            AccountBalance::refelectAccountBalance($journalTransaction);

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

            AccountBalance::refelectAccountBalance($contactTransactionRequest);

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

    public function getBalance($accountId)
    {
        $account = Account::getOne($accountId);
        if (!$account) {
            return $this->sendError('Account not found.', [], 404);
        }
        $allAccounts = Account::getAll();

        $accountIds = [$account->id];

        foreach ($allAccounts as $eachAccount) {
            if ($eachAccount->parent_tree_ids != null && in_array($accountId, $eachAccount->parent_tree_ids)) {
                $accountIds[] = $eachAccount->id;
            }
        }

        $balance = AccountBalance::getAccountsBalance($accountIds);

        return $this->sendResponse($balance, "Total Balance of account #${accountId} and his sub accounts.");
        
    }
}
