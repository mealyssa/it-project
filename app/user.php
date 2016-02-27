<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    
    protected $table = "users";
    protected $primarykey = "id";
    

    protected $fillable = ['username','password','first_name','last_name','contact_number','role','email'];
    protected $hidden = ["password","remember_token"]; 
    public $timestamps = false;
}
