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
}
