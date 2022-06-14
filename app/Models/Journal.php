<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journals';
    protected $fillable = [
        'credit_account_id',
        'debit_account_id',
        'amount',
        'comment',
        'reference_id',
        'contact_id',
    ];


    public static function getAll()
    {
        return Journal::all()->where('deleted', 0);
    }

    public static function getOne($journalId)
    {
        return Journal::where([
            ['id', $journalId],
            ['deleted', 0]
        ])->first();
    }


    public static function addRow($data)
    {
        return Journal::create([
            'credit_account_id' => $data->credit_account_id,
            'debit_account_id' => $data->debit_account_id,
            'amount' => $data->amount,
            'comment' => $data->comment,
            'reference_id' => $data->reference_id,
            'contact_id' => $data->contact_id,
        ]);
    }
}
