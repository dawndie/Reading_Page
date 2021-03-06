<?php

namespace App\Http\Controllers\ControllerUser\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register()
    {
        return view('user.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function postRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'name.required' => 'B???n ch??a ??i???n t??n',
            'name.max' => 'T??n ch??? t???i ??a 255 k?? t???',
            'email.required' => 'B???n ch??a ??i???n Email',
            'email.unique' => 'Email n??y ???? ???????c s??? d???ng',
            'email.max' => 'Email ch??? t???i ??a 255 k?? t???',
            'email.email' => 'Kh??ng ????ng ?????nh d???ng Email',
            'password.required' => 'B???n ch??a ??i???n m???t kh???u',
            'password.confirmed' => 'X??c nh???n m???y kh???u kh??ng kh???p',
            'password.min' => 'M???t kh???u ??t nh???t ph???i c?? 6 k?? t???',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 0,
            'status' => 1
        ]);

        return redirect()->route('register')->with('success', '????ng k?? th??nh c??ng! Ch??ng t??i s??? xem s??t c???p quy???n truy c???p cho ban');
    }
}
