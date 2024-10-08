<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'us_name' => 'required|string|max:255',
            'us_lastName' => 'required|string|max:255',
            'us_dni' => 'required|string|unique:tbl_user,us_dni',
            'us_email' => 'required|string|email|max:255|unique:tbl_user,us_email',
            'password' => 'required|string|min:8|confirmed',
            'us_address' => 'nullable|string|max:255',
            'us_first_phone' => 'required|string|max:15',
            'us_second_phone' => 'nullable|string|max:15',
            'us_image' => 'nullable|image|max:2048',
        ];
    }

}
