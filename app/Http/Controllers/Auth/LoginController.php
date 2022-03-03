<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use File;
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
        $officer_id =   strtoupper($request->officer_id);
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

    public function profil()
    {
        $data['title']      =   'Profil Saya';
        $data['officer']    =   User::findOrFail(Auth::user()->id);

        return view('auth.profil', $data);
    }

    public function updateProfil(Request $request)
    {
        $officer    =   User::findOrFail(Auth::user()->id);
        $image_name =   $officer->image;

        $officer_id =   strtoupper($officer->officer_id);
        $image      =   $request->image;

        $validate   =   Validator::make($request->all(), [
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'place_of_birth'    =>  'required|max:255',
                            'date_of_birth'     =>  'required|date',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:10,15|unique:users,phone,' . Auth::user()->id,
                            'image'             =>  'max:5120',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'fullname.required'         =>  'Nama Lengkap wajib diisi',
                            'fullname.max'              =>  'Nama Lengkap maksimal 255 karakter',
                            'fullname.regex'            =>  'Nama Lengkap hanya boleh huruf dan spasi',
                            'place_of_birth.required'   =>  'Tempat Lahir wajib diisi',
                            'place_of_birth.max'        =>  'Tempat Lahir maksimal 255 karakter',
                            'date_of_birth.required'    =>  'Tanggal Lahir wajib diisi',
                            'date_of_birth.date'        =>  'Tanggal Lahir tidak valid',
                            'gender.required'           =>  'Jenis Kelamin wajib dipilih',
                            'gender.in'                 =>  'Jenis Kelamin tidak valid',
                            'address.required'          =>  'Alamat wajib diisi',
                            'phone.required'            =>  'Telepon wajib diisi',
                            'phone.digits_between'      =>  'Telepon 10 - 15 angka',
                            'phone.unique'              =>  'Telepon sudah digunakan',
                            'image.max'                 =>  'Foto maksimal 5 Mb',
                            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        if($image != NULL) {
            if($officer->image != NULL) {
                File::delete('uploads/users/' . $officer->image);
            }

            $image_name =   'users_' . $officer_id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/users'), $image_name);
        }

        $user   =   array(
                    'fullname'          =>  ucwords(strtolower($request->fullname)),
                    'place_of_birth'    =>  ucwords(strtolower($request->place_of_birth)),
                    'date_of_birth'     =>  $request->date_of_birth,
                    'gender'            =>  $request->gender,
                    'address'           =>  $request->address,
                    'phone'             =>  $request->phone,
                    'image'             =>  $image_name,
                );

        User::whereId(Auth::user()->id)->update($user);

        return response()->json(['success' => 'Profil Berhasil Diupdate']);
    }

    public function password()
    {
        $data['title']  =   'Ubah Password';

        return view('auth.password', $data);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password'          =>  'required',
            'new_password'      =>  'required|min:8|different:password',
            'confirm_password'  =>  'required|min:8|same:new_password',
        ],
        [
            'password.required'         =>  'Password Saat Ini wajib diisi',
            'new_password.required'     =>  'Password Baru wajib diisi',
            'new_password.min'          =>  'Password Baru minimal 8 karakter',
            'new_password.different'    =>  'Password Baru tidak boleh sama dengan Password Saat Ini',
            'confirm_password.required' =>  'Ulangi Password Baru wajib diisi',
            'confirm_password.min'      =>  'Ulangi Password Baru minimal 8 karakter',
            'confirm_password.same'     =>  'Ulangi Password Baru wajib sama dengan Password Baru',
        ]);

        if(Hash::check($request->password, Auth::user()->password)) {
            $user           =   User::findOrFail(Auth::user()->id);
            $user->password =   Hash::make($request->new_password);
            $user->save();

            return redirect()->route('logout')->with('error', 'Password Berhasil Diubah, Silakan Login Ulang');
        } else {
            return redirect()->back()->with('error', 'Password Saat Ini Salah');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
