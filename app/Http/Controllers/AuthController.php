<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = Str::lower($validated['email']);
        $throttleKey = $this->throttleKey($request, $email);
        $user = User::where('email', $email)->first();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ]);
        }

        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            throw ValidationException::withMessages([
                'email' => 'Akun dikunci sementara sampai ' . $user->locked_until->translatedFormat('d M Y H:i') . '.',
            ]);
        }

        if ($user && ! $user->is_active) {
            RateLimiter::hit($throttleKey, 300);

            return back()
                ->withErrors(['email' => 'Akun belum aktif atau sudah dinonaktifkan. Hubungi admin desa.'])
                ->onlyInput('email');
        }

        $credentials = [
            'email' => $email,
            'password' => $validated['password'],
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = $request->user();

            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();

            $user->forceFill([
                'current_session_id' => $request->session()->getId(),
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_count' => $user->login_count + 1,
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ])->save();

            RateLimiter::clear($throttleKey);

            return redirect()->intended($this->redirectPathFor($user));
        }

        $this->registerFailedLogin($user, $throttleKey);

        return back()
            ->withErrors(['email' => 'Email atau password tidak sesuai, atau akun belum aktif.'])
            ->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->session()->getId();

        if ($user && $user->current_session_id === $sessionId) {
            $user->forceFill(['current_session_id' => null])->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password sudah dibuat. Untuk mode lokal, cek file log Laravel.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login kembali.')
            : back()->withErrors(['email' => __($status)]);
    }

    private function registerFailedLogin(?User $user, string $throttleKey): void
    {
        RateLimiter::hit($throttleKey, 300);

        if (! $user) {
            return;
        }

        $attempts = $user->failed_login_attempts + 1;

        $user->forceFill([
            'failed_login_attempts' => $attempts,
            'locked_until' => $attempts >= 5 ? now()->addMinutes(15) : null,
        ])->save();
    }

    private function throttleKey(Request $request, string $email): string
    {
        return 'login|' . $email . '|' . $request->ip();
    }

    private function redirectPathFor(User $user): string
    {
        return $user->hasRole('warga')
            ? route('citizen-portal')
            : route('dashboard');
    }
}
