<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    
    protected $table = "users";
    protected $primarykey = "id";
    

    protected $fillable = ['username','password','first_name','last_name','contact_number','email'];
    protected $hidden = ["password","remember_token"]; 
    public $timestamps = false;
}
