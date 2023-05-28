<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OfficerRequest;

use Auth;
use File;
use Hash;

use App\Models\User;

class OfficerController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Petugas';

        if(request()->ajax()) {
            return datatables()->of(User::where('isAdmin', false)->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    if(Auth::user()->id != $data->id) {
                        $button =  '<button type="button" id="'.$data->id.'" class="btnReset btn btn-success" title="Reset Password"><i class="fas fa-unlock"></i></button>';
                        $button .=   '<a href="' . route('officer.show', $data->id) . '" class="btn btn-info ml-2" title="Detail"><i class="fas fa-eye"></i></a>';
                        $button .=   '<a href="' . route('officer.edit', $data->id) . '" class="btn btn-warning mx-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                        $button .=  '<button type="button" id="'.$data->id.'" class="btnDelete btn btn-danger" title="Hapus"><i class="fas fa-eraser"></i></button>';

                        return $button;
                    }
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.officer.index', $data);
    }

    public function create()
    {
        $data['title']      =   'Tambah Petugas';
        $data['button']     =   'Simpan';
        $data['officer']    =   '';

        return view('data.officer.form', $data);
    }

    public function store(OfficerRequest $request)
    {
        try {
            $officer_id =   strtoupper($request->officer_id);
            $path       =   'uploads/officers';
            $image      =   $request->image;
            $image_name =   NULL;

            if($image != NULL) {
                $image_name =   'officers_' . $officer_id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($path), $image_name);
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

            return response()->json(['messages' => 'Petugas Berhasil Disimpan']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Petugas Gagal Disimpan']);
        }
    }

    public function show($id)
    {
        try {
            $data['title']      =   'Detail Petugas';
            $data['officer']    =   User::where('isAdmin', false)->findOrFail($id);

            return view('data.officer.detail', $data);
        } catch (\Throwable $th) {
            return redirect()->route('officer.index');
        }
    }

    public function edit($id)
    {
        try {
            $data['title']      =   'Detail Petugas';
            $data['button']     =   'Update';
            $data['officer']    =   User::where('isAdmin', false)->findOrFail($id);

            return view('data.officer.form', $data);
        } catch (\Throwable $th) {
            return redirect()->route('officer.index');
        }
    }

    public function update(OfficerRequest $request)
    {
        try {
            $officer    =   User::where('isAdmin', false)->findOrFail($id);

            $path       =   'uploads/officers';
            $image_name =   $officer->image;

            $officer_id =   strtoupper($request->officer_id);
            $image      =   $request->image;

            if($image != NULL) {
                File::delete('uploads/officers/' . $officer->image);
    
                $image_name =   'officers_' . $officer_id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($path), $image_name);
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
            return response()->json(['messages' => 'Petugas Berhasil Diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Petugas Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        try {
            $officer    =   User::findOrFail($id);
            $officer->delete();

            return response()->json(['messages' => 'Petugas Berhasil Dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Petugas Gagal Dihapus']);
        }
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

        return view('data.officer.trash', $data);
    }

    public function restore($id)
    {
        try {
            $officer    =   User::withTrashed()->findOrFail($id);
            $officer->restore();

            return response()->json(['messages' => 'Petugas Berhasil Dipulihkan']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Petugas Gagal Dipulihkan']);
        }
    }

    public function reset($id)
    {
        try {
            $officer            =   User::findOrFail($id);
            $officer->password  =   Hash::make($officer->officer_id);
            $officer->save();

            return response()->json(['messages' => 'Password Petugas Berhasil Direset']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Password Petugas Gagal Direset']);
        }
    }
}
