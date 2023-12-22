<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'username',
        'firstname',
        'lastname',
        'type',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
