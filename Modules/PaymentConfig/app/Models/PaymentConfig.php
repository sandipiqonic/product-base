<?php

namespace Modules\PaymentConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PaymentConfig\Database\Factories\PaymentConfigFactory;

class PaymentConfig extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['gateway', 'config'];

    protected $casts = [
        'config' => 'array',
    ];
    
    protected static function newFactory(): PaymentConfigFactory
    {
        //return PaymentConfigFactory::new();
    }

}
