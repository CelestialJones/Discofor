<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Throwable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (Throwable $e) {
            report($e);

            return back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Nao foi possivel enviar o email agora. Verifique a configuracao SMTP e tente novamente.',
                ]);
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        if ($status === Password::RESET_THROTTLED) {
            $seconds = (int) config('auth.passwords.users.throttle', 30);

            return back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Aguarde {$seconds} segundos antes de tentar novamente.",
                ]);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
