<?php

namespace App\Services\Interfaces;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function register(Request $request);


}
