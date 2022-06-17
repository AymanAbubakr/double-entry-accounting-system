<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use App\Jobs\TransactionJobs;
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
        if ($transactionRequest->credit_account_id == $transactionRequest->debit_account_id) {
            return $this->sendError('Debit and credit accounts cannot be the same.', [], 400);
        }

        $transactionRequest['transactionType'] = 'store';
        $this->dispatch(new TransactionJobs($transactionRequest->all()));

        return $this->sendResponse([], 'Transaction placed successfully.');
    }

    public function revertTransaction($journalId)
    {

        try {
            $journalTransaction = Journal::getOne($journalId);

            if ($journalTransaction == null) {
                return $this->sendError('Transaction not found.', [], 404);
            }

            $journalTransaction['transactionType'] = 'revert';
            $this->dispatch(new TransactionJobs($journalTransaction));

            return $this->sendResponse([], 'Transaction placed successfully.');
        } catch (\Exception $exp) {
            return $this->sendError($exp->getMessage(), [], 400);
        }
    }

    public function contactTransaction(ContactTransactionRequest $contactTransactionRequest)
    {
        try {

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

            $contactTransactionRequest['transactionType'] = 'contact';
            $this->dispatch(new TransactionJobs($contactTransactionRequest->all()));

            return $this->sendResponse([], 'Transaction placed successfully.');
        } catch (\Exception $exp) {
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
