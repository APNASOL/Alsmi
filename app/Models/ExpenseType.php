<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'expense_types';
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_type_id');
    }
    
}

