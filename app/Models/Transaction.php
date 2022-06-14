<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'credit_account_id',
        'debit_account_id',
        'transaction_type',
        'amount',
    ];

    public static function getAll(Request $request)
    {
        if ($request->query('type') == 'single') {
            return Journal::getAll();
        } else {
            return Transaction::all()->where('deleted', 0);
        }
    }

    public static function batchInsert($transactionRequest, $journalId)
    {
        return Transaction::insert(
            [
                [
                    'credit_account_id' => $transactionRequest->credit_account_id,
                    'debit_account_id' => $transactionRequest->debit_account_id,
                    'amount' => $transactionRequest->amount,
                    'transaction_type' => 'credit',
                    'journal_id' => $journalId,
                ],
                [
                    'credit_account_id' => $transactionRequest->credit_account_id,
                    'debit_account_id' => $transactionRequest->debit_account_id,
                    'amount' => $transactionRequest->amount,
                    'transaction_type' => 'debit',
                    'journal_id' => $journalId,
                ],
            ]
        );
    }
}
