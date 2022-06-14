<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAccount extends Model
{
    use HasFactory;

    protected $table = 'type_account';
    protected $fillable = [
        'type_id',
        'account_id',
        'deleted',
    ];

    public static function getAll()
    {
        return TypeAccount::all()->where('deleted', 0);
    }

    public static function canProcessTransaction($creditAccountId, $debitAccountId, $typeId)
    {

        $typeAccounts = TypeAccount::whereIn(
            'id',
            [$creditAccountId, $debitAccountId]
        )->where(
            [
                ['deleted', 0],
                ['type_id', $typeId],
            ]
        )->get();

        $isCreditAccountFound = false;
        $isDebitAccountFound = false;


        foreach ($typeAccounts as $typeAccount) {
            if ($typeAccount->account_id == $creditAccountId) {
                $isCreditAccountFound = true;
            } else if ($typeAccount->account_id == $debitAccountId) {
                $isDebitAccountFound = true;
            }
        }

        return $isCreditAccountFound && $isDebitAccountFound;
    }
}
