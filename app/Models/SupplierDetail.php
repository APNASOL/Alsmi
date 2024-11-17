<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierDetail extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'suppliers_details';
    
}

