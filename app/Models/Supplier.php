<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'suppliers';
    protected $fillable = [
        'id', 
        'name',
        'contact',
        'address', 
        'status',
        'created_at',
        'updated_at'
    ];
}
