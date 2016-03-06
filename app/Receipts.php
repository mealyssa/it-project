<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipts extends Model
{
    protected $table = "receipts";
    protected $primarykey = "id";
    

    protected $fillable = ['name','path','event','user_id'];
    public $timestamps = false;
}
