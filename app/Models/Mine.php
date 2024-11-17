<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mine extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table = 'mines';
    protected $fillable = [
        'id', 
        'mine_name',
        'mine_no',
        'mine_place',
        'security',
        'status',
        'created_at',
        'updated_at'
    ];
}
