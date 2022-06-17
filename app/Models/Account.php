<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'accounts';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'parent_id', 'parent_tree_ids', 'deleted'];


    public static function getAll()
    {
        return Account::all()->where('deleted', 0);
    }

    public static function getOne($accountId)
    {
        return Account::where([
            ['id', $accountId],
            ['deleted', 0]
        ])->first();
    }

    protected $casts = [
        'parent_tree_ids' => 'array',
    ];
}
