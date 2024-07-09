<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Flasher\Prime\FlasherInterface;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class LoginController extends Controller
{
    
    public function user()
    {
        // if(Auth::id()>0){
        //     return redirect()->route('main');
        // }
        return view('user');
    }

    //showRegisterForm
    public function showRegisterForm()
    {
        if(Auth::id()>0){
            return redirect()->route('main');
        }
        return view('register');
    }
    public function showLoginForm()
    {
        if(Auth::id()>0){
            return redirect()->route('main');
        }
        return view('login');
    }
    
    public function login(Request $request, FlasherInterface $flasher)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            toastr()->success('Đăng nhập thành công!');
            return redirect()->route('main')->with([
                'username' => $user->name
            ]);
        } else {
            toastr()->error('Đăng nhập thất bại!');
            return redirect()->route('login');
        }
    }

    public function register(Request $request, FlasherInterface $flasher)
    {
        
        // Validate the form data
        $credentials = $request->validate([
            // 'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $user = User::where('email', $credentials)->first();

        if (!$user) {
            User::factory()->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
            toastr()->success('Tao tai khoan thanh cong!');
            
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                toastr()->success('Đăng nhập thành công!');
                return redirect()->route('main')->with([
                    'username' => $user->name
                ]);
            } else {
                toastr()->error('Đăng nhập thất bại!');
                return redirect()->route('login');
            }
            return redirect()->back();
        } else {
            toastr()->error('User đã tồn tại.');
            return redirect()->back();
        }
    }

    // Endpoint xử lý từng số
    public function showMainView()
    {
        return view('main');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}
