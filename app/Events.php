<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
     protected $table = "events";
    protected $primarykey = "id";
    

    protected $fillable = ['event_name','user_id','org_id','budget','start_date','end_date'];
    public $timestamps = false;
}
