<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MakeLoginRequest;

class LoginController extends Controller
{
    public function login(MakeLoginRequest $request)
    {
        if ($request->attempt()) {
            return to_route('dashboard');
        }

        return back()->with(['message' => 'Não encontrado']);
    }
}
