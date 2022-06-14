<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type_id',
        'deleted',
    ];

    public static function getAll()
    {
        return Contact::all()->where('deleted', 0);
    }
}
