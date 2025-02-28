<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'income_types';
    public function incomes()
    {
        return $this->hasMany(Income::class, 'income_type_id');
    }
}

