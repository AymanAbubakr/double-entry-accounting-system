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
}
