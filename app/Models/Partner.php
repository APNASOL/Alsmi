<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'partners';
    protected $fillable = [
        'id',
        'name',
        'contact',
        'address',
        'status'
    ];
}

