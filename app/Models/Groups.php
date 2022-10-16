<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;
    protected $table = 'groups';
    protected $primaryKey = 'group_id';
    protected $fillable = ['group_id',
                         'group_name',
                         'group_desc',
                         'subject_id'];
     public $timestamps = false;
}
