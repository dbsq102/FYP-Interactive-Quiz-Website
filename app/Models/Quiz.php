<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quiz';
    protected $primaryKey = 'quiz_id';
    protected $fillable = ['quiz_id',
                         'quiz_title', 
                         'quiz_summary', 
                         'gamemode_id',
                         'time_limit', 
                         'group_id', 
                         'items', 
                         'subject_id', 
                         'user_id'] ;
     public $timestamps = false;
}
