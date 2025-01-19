<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpanseType extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'expense_types';
    
}

