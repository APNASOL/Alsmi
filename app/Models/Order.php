<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'customer_id',
        'start_date',
        'end_date',
        'status'
    ];
}

