<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'group_members';
    protected $primaryKey = 'member_id';
    protected $fillable = ['member_id',
                         'user_id',
                         'group_id'] ;
     public $timestamps = false;
}
