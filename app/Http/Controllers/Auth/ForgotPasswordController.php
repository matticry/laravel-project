<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'us_email' => 'required|email|exists:tbl_user,us_email',
        ]);

        // Obtener el usuario por su email
        $user = User::where('us_email', $request->us_email)->first();

        // Generar token
        $token = Str::random(60);

        // Guardar token en la tabla tbl_token
        Token::create([
            'usu_id' => $user->us_id,
            'token' => $token,
            'created_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMinutes(60), // Token expira en 60 minutos
        ]);

        // Enviar correo con el enlace de recuperación
        $resetLink = route('password.reset', ['token' => $token]);

        Mail::send('emails.reset-password', ['resetLink' => $resetLink], function ($message) use ($user) {
            $message->to($user->us_email);
            $message->subject('Recuperación de Contraseña');
        });

        return back()->with('status', 'Enlace de recuperación enviado al correo.');
    }
}
