<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

        $typeAccounts = DB::table('type_account')->whereIn(
            'account_id',
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


    public static function handlerAssigning($request, $contact)
    {
        try {
            DB::beginTransaction();

            DB::table('type_account')->update(
                [
                    'deleted' => 1,
                ],
                [
                    'type_id' => $contact->type_id,
                    'deleted' => 0,
                ]
            );
            $dataToInsert = [];

            foreach ($request['accounts'] as $accounntId) {
                $dataToInsert[] = [
                    'type_id' => $contact->type_id,
                    'account_id' => $accounntId,
                    'deleted' => 0,
                ];
            }
            if (count($dataToInsert) > 0) {
                DB::table('type_account')->insert($dataToInsert);
            }

            DB::commit();
            return true;
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
