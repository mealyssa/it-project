<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class Registration_Request extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'=>'required|alpha',
            'last_name' =>'required|alpha',
            'username'  =>'required',
            'password'  =>'required',
            'contact_number' => 'required|numeric',
            'email'     =>'required|email|unique:users'
        ];
    }
}
