<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Hash;
use Validator;

use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        $data['title']  =   'Form Login';

        return view('auth.login', $data);
    }

    public function store(Request $request)
    {
        $officer_id =   strtolower($request->officer_id);
        $password   =   $request->password;

        $this->validate($request, [
            'officer_id'    =>  'required|max:20|regex:/^[a-zA-Z0-9]*$/',
            'password'      =>  'required',
        ],
        [
            'officer_id.required'   =>  'Nomor Petugas wajib diisi',
            'officer_id.max'        =>  'Nomor Petugas maksimal 20 karakter',
            'officer_id.regex'      =>  'Nomor Petugas wajib huruf atau angka',
            'password.required'     =>  'Password wajib diisi',
        ]);

        if (Auth::attempt(['officer_id' => $officer_id, 'password' => $password, 'deleted_at' => null])) {
            $user = Auth::getLastAttempted();

            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with('error', 'Nomor Petugas atau Password salah');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
