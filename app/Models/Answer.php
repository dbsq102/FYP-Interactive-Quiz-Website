<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answer_bank';
    protected $primaryKey = 'ans_id';
    protected $fillable = ['ans_id',
                         'ques_id',
                         'correct',
                         'answer',
                         'ans_no'] ;
     public $timestamps = false;
}
