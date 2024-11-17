<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'customers';
    protected $fillable = [
        'id',
        'name',
        'contact',
        'address',
        'status'
    ];
}

