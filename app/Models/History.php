<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $table = 'history';
    protected $primaryKey = 'history_id';
    protected $fillable = ['history_id',
                         'quiz_id',
                         'score',
                         'time_taken',
                         'date_taken'] ;
     public $timestamps = false;
}
