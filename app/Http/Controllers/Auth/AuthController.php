<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'Email tidak terdaftar.'], 400);
            } else if (!Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Password salah.'], 400);
            } else {
                $user = User::where('email', $request->email)->first();
                Session::put('user', $user);
                if ($user->role == 'user') {
                    return response()->json([
                        'success' => "Login as $user->name Berhasil",
                        'route' => route('home') // Assuming this route exists for users
                    ], 200);
                } else if ($user->role == 'admin') {
                    return response()->json([
                        'success' => "Login as Admin Berhasil",
                        'route' => route('admin.dashboard')
                    ], 200);
                } else {
                    return response()->json([
                        'success' => "Login as Owner Berhasil",
                        'route' => route('admin.dashboard')
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegistrationForm()
    {
        if ($this->checkAdminAuth()) {
            return redirect()->intended(route('register'));
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6'
            ]);

            // Pengecekan manual email sudah terdaftar sebelum validasi
            if (User::where('email', strtolower(trim($request->email)))->exists()) {
                return response()->json([
                    'error' => 'Email ini sudah terdaftar. Gunakan email lain atau lupa password?'
                ], 409); // 409 Conflict status code
            }

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
                'name' => $validated['name'],
                'phone' => '',
                'address' => '',
                'image' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),

            ]);
            $user = User::where('email', $request->email)->first();
            Session::put('user', $user);
            return response()->json(['success' => 'Berhasil membuat akun'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function profile()
    {
        if (!$this->checkAdminAuth()) {
            return redirect()->route('login'); // Ganti dengan rute login yang sesuai
        }
        return view('auth.profile', [
            'user' => Auth::user()
        ]);
    }


    private function checkAdminAuth()
    {
        $user = Session::get('user');
        if ($user != null && $user->role === 'admin') {
            return true; // Pengguna sudah login sebagai admin
        }
        return false; // Pengguna belum login
    }

    private function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

    private function sendLoginResponse($route)
    {
        return;
    }

    private function sendFailedLoginResponse()
    {
        return response()->json(['error' => 'Email atau password salah.'], 400);
    }

    private function sendErrorResponse($message)
    {
        return response()->json(['error' => $message], 400);
    }
}
