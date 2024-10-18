<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;



    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $tokenRecord = Token::where('token', $request->token)
            ->where('is_revoked', 0)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$tokenRecord) {
            return back()->withErrors(['token' => 'El token es inválido o ha expirado.']);
        }

        $user = User::find($tokenRecord->usu_id);
        $user->us_password = Hash::make($request->password);  // Usa $request->password aquí
        $user->save();

        $tokenRecord->update(['is_revoked' => 1]);

        return redirect()->route('login')->with('status', 'Contraseña restablecida con éxito.');
    }


}
