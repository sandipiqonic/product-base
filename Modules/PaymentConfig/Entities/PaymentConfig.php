<?php

namespace Modules\PaymentConfig\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentConfig extends Model
{
    protected $fillable = ['gateway', 'config'];

    protected $casts = [
        'config' => 'array',
    ];
}
