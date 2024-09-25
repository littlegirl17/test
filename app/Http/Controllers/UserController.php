<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VerificationCode;
use App\Http\Requests\LoginValidate;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\VerifyCodeRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(LoginValidate $request)
    {

        $user = $this->userModel->checkAccount($request->email);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Tài khoản không tồn tại trong hệ thống!');
        }

        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            if ($user->status < 1) {
                return redirect()->route('login')->with('error', 'Tài khoản bạn đã bị khóa');
            }
            session()->put('user', $user);
            return redirect('/')->with('success', 'Đăng nhập thành công');
        }
        return redirect()->route('login')->with('error', 'Email hoặc password của bạn đã sai!');
    }
}
