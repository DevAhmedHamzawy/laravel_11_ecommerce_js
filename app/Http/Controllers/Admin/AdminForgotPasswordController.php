<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Notifications\AdminResetPasswordNotification;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminForgotPasswordController extends Controller
{
    // Show form to request password reset
    public function showForgetPasswordForm()
    {
        return view('admin.passwords.email');
    }

    // Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        // العثور على الـ Admin بواسطة البريد الإلكتروني
        $admin = Admin::where('email', $request->email)->first();

        // إرسال إشعار إعادة تعيين كلمة المرور
        $admin->notify(new AdminResetPasswordNotification(
            Password::broker('admins')->createToken($admin)
        ));

        return back()->with('success', trans('forgot_password.password_link_sent'));
    }

    // Show reset form
    public function showResetPasswordForm($token)
    {
        return view('admin.passwords.reset', ['token' => $token, 'email' => request()->email]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
