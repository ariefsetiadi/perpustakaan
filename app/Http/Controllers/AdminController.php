<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use File;
use Hash;
use Validator;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Petugas';

        if(request()->ajax()) {
            return datatables()->of(User::where('isAdmin', false)->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    if(Auth::user()->id != $data->id) {
                        $button =   '<a href="' . route('petugas.show', $data->id) . '" class="btn btn-info" title="Detail"><i class="fas fa-eye"></i></a>';
                        $button .=   '<a href="' . route('petugas.edit', $data->id) . '" class="btn btn-warning mx-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $button .=  '<button type="button" id="'.$data->id.'" class="btnDelete btn btn-danger" title="Hapus"><i class="fas fa-eraser"></i></button>';

                        return $button;
                    }
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('admin.index', $data);
    }

    public function create()
    {
        $data['title']      =   'Tambah Petugas';
        $data['button']     =   'Simpan';
        $data['officer']    =   '';

        return view('admin.form', $data);
    }

    public function store(Request $request)
    {
        $officer_id =   strtoupper($request->officer_id);
        $image      =   $request->image;
        $image_name =   NULL;

        $validate   =   Validator::make($request->all(), [
                            'officer_id'        =>  'required|max:20|regex:/^[a-zA-Z0-9]*$/||unique:users,officer_id',
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'place_of_birth'    =>  'required|max:255',
                            'date_of_birth'     =>  'required|date',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:10,15|unique:users,phone',
                            'image'             =>  'max:5120',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'officer_id.required'       =>  'Nomor Petugas wajib diisi',
                            'officer_id.max'            =>  'Nomor Petugas maksimal 20 karakter',
                            'officer_id.regex'          =>  'Nomor Petugas hanya boleh huruf dan spasi',
                            'officer_id.unique'         =>  'Nomor Petugas sudah digunakan',
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
            $image_name =   'users_' . $officer_id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/users'), $image_name);
        }

        $user                   =   new User;
        $user->officer_id       =   $officer_id;
        $user->fullname         =   ucwords(strtolower($request->fullname));
        $user->isAdmin          =   false;
        $user->place_of_birth   =   ucwords(strtolower($request->place_of_birth));
        $user->date_of_birth    =   $request->date_of_birth;
        $user->gender           =   $request->gender;
        $user->address          =   $request->address;
        $user->phone            =   $request->phone;
        $user->image            =   $image_name;
        $user->password         =   Hash::make($officer_id);
        $user->save();

        return response()->json(['success' => 'Petugas Berhasil Disimpan']);
    }

    public function show($id)
    {
        $data['title']      =   'Detail Petugas';
        $data['officer']    =   User::where('isAdmin', false)->findOrFail($id);

        return view('admin.detail', $data);
    }

    public function edit($id)
    {
        $data['title']      =   'Edit Petugas';
        $data['button']     =   'Update';
        $data['officer']    =   User::where('isAdmin', false)->findOrFail($id);

        return view('admin.form', $data);
    }

    public function update(Request $request)
    {
        $officer    =   User::where('isAdmin', false)->findOrFail($request->off_id);
        $image_name =   $officer->image;

        $officer_id =   strtoupper($request->officer_id);
        $image      =   $request->image;

        $validate   =   Validator::make($request->all(), [
                            'officer_id'        =>  'required|max:20|regex:/^[a-zA-Z0-9]*$/||unique:users,officer_id,' . $request->off_id,
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'place_of_birth'    =>  'required|max:255',
                            'date_of_birth'     =>  'required|date',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:10,15|unique:users,phone,' . $request->off_id,
                            'image'             =>  'max:5120',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'officer_id.required'       =>  'Nomor Petugas wajib diisi',
                            'officer_id.max'            =>  'Nomor Petugas maksimal 20 karakter',
                            'officer_id.regex'          =>  'Nomor Petugas hanya boleh huruf dan spasi',
                            'officer_id.unique'         =>  'Nomor Petugas sudah digunakan',
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
            File::delete('uploads/users/' . $officer->image);

            $image_name =   'users_' . $officer_id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/users'), $image_name);
        }

        $user   =   array(
                    'officer_id'        =>  $request->officer_id,
                    'fullname'          =>  ucwords(strtolower($request->fullname)),
                    'place_of_birth'    =>  ucwords(strtolower($request->place_of_birth)),
                    'date_of_birth'     =>  $request->date_of_birth,
                    'gender'            =>  $request->gender,
                    'address'           =>  $request->address,
                    'phone'             =>  $request->phone,
                    'image'             =>  $image_name,
                );

        User::whereId($request->off_id)->update($user);

        return response()->json(['success' => 'Petugas Berhasil Diupdate']);
    }

    public function destroy($id)
    {
        $officer    =   User::findOrFail($id);
        $officer->delete();

        return response()->json(['success' => 'Petugas Berhasil Dihapus']);
    }

    public function trash()
    {
        $data['title']  =   'Trash Petugas';

        if(request()->ajax()) {
            return datatables()->of(User::onlyTrashed()->where('isAdmin', false)->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    if(Auth::user()->id != $data->id) {
                        $button =  '<button type="button" id="'.$data->id.'" class="btnRestore btn btn-success" title="Pulihkan"><i class="fas fa-trash-restore"></i></button>';

                        return $button;
                    }
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('admin.trash', $data);
    }

    public function restore($id)
    {
        $officer    =   User::withTrashed()->findOrFail($id);
        $officer->restore();

        return response()->json(['success' => 'Petugas Berhasil Dipulihkan']);
    }
}
