<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountBalance extends Model
{
    use HasFactory;

    protected $table = 'account_balances';
    protected $fillable = [
        'account_id',
        'total_credit_received',
        'total_credit_sent',
        'total_balance',
        'deleted',
    ];

    public static function getOne($accountId)
    {
        return AccountBalance::where([
            ['account_id', $accountId],
            ['deleted', 0]
        ])->first();
    }

    private static function insertNewAccount($transaction)
    {
        return AccountBalance::create([
            'account_id' => $transaction->account_id,
            'total_credit_received' => $transaction->credit_received,
            'total_credit_sent' => $transaction->credit_sent,
            'total_balance' => $transaction->credit_received - $transaction->credit_sent,
        ]);
    }

    private static function updateAccount($transaction, $accountBalance, $transactionType = 'sender')
    {

        if ($transactionType == 'sender') {
            $accountBalance->total_credit_sent += $transaction->amount;
            $accountBalance->total_balance -= $transaction->amount;
        } else {
            $accountBalance->total_credit_received += $transaction->amount;
            $accountBalance->total_balance += $transaction->amount;
        }

        $accountBalance->save();
    }

    public static function refelectAccountBalance($transaction)
    {
        $senderAccount = AccountBalance::getOne($transaction->credit_account_id);
        $receiverAccount = AccountBalance::getOne($transaction->debit_account_id);

        if (!$senderAccount) {
            AccountBalance::insertNewAccount(
                (object)[
                    'account_id' => $transaction->credit_account_id,
                    'credit_received' => 0,
                    'credit_sent' => $transaction->amount,
                ]
            );
        } else {
            AccountBalance::updateAccount(
                $transaction,
                $senderAccount,
                'sender'
            );
        }

        if (!$receiverAccount) {
            AccountBalance::insertNewAccount(
                (object)[
                    'account_id' => $transaction->debit_account_id,
                    'credit_received' => $transaction->amount,
                    'credit_sent' => 0,
                ]
            );
        } else {
            AccountBalance::updateAccount(
                $transaction,
                $receiverAccount,
                'receiver'
            );
        }

        return true;
    }

    public static function getAccountsBalance($accountIds)
    {
        $accountBalance = AccountBalance::whereIn('account_id', $accountIds)
            ->where('deleted', 0)
            ->select(
                DB::raw('SUM(total_credit_received) as total_credit_received'),
                DB::raw('SUM(total_credit_sent) as total_credit_sent'),
                DB::raw('SUM(total_balance) as total_balance')
            )->get();

        return $accountBalance;
    }
}
