<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'ثبت‌نام با موفقیت انجام شد!');
    }

    protected function create(array $data)
    {
        // ثبت کاربر
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // ایجاد tenant جدید (دیتابیس جدا)
        $tenant = Tenant::create([
            'id' => 'tenant_' . $user->id, // کلید یکتا برای tenant
            'data' => [
                'user_id' => $user->id,
                // داده‌های اضافی دلخواه
            ]
        ]);

        // ایجاد دیتابیس tenant و اجرای مایگریشن tenant
        $tenant->createDatabase();
        $tenant->runMigrations();

        // اگر خواستی ریلیشن بزنی
        // $user->tenant()->associate($tenant);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        $tenant = Tenant::where('data->user_id', $user->id)->first();
        if ($tenant) {
            tenancy()->initialize($tenant);
        }
    }
}
