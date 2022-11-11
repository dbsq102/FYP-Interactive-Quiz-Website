<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'question_bank';
    protected $primaryKey = 'ques_id';
    protected $fillable = ['ques_id',
                         'quiz_id',
                         'type_id',
                         'question',
                         'ques_no'] ;
     public $timestamps = false;
}
