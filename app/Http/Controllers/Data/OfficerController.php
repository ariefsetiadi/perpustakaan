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

        if (request()->ajax()) {
            return datatables()->of(User::where('isAdmin', false)->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    if (Auth::user()->id != $data->id) {
                        $button =  '<button type="button" id="'.$data->id.'" class="btnReset btn btn-success" title="Reset Password"><i class="fas fa-unlock"></i></button>';
                        $button .=   '<a href="' . route('officer.show', $data->id) . '" class="btn btn-info ml-2" title="Detail"><i class="fas fa-eye"></i></a>';
                        $button .=   '<a href="' . route('officer.edit', $data->id) . '" class="btn btn-warning mx-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';

                        return $button;
                    }
                })->editColumn('status', function($data) {
                    if ($data->status == TRUE) {
                        $status =  '<h5><span class="badge badge-success">AKTIF</span></h5>';
                    } else {
                        $status =  '<h5><span class="badge badge-danger">NONAKTIF</span></h5>';
                    }

                    return $status;
                })->rawColumns(['action', 'status'])->addIndexColumn()->make(true);
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

            $user                   =   new User;
            $user->officer_id       =   $officer_id;
            $user->fullname         =   ucwords(strtolower($request->fullname));
            $user->place_of_birth   =   ucwords(strtolower($request->place_of_birth));
            $user->date_of_birth    =   $request->date_of_birth;
            $user->gender           =   $request->gender;
            $user->address          =   $request->address;
            $user->phone            =   $request->phone;
            $user->isAdmin          =   false;
            $user->status           =   $request->status;
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
            $officer    =   User::where('isAdmin', false)->findOrFail($request->off_id);

            $user   =   array(
                            'officer_id'        =>  $request->officer_id,
                            'fullname'          =>  ucwords(strtolower($request->fullname)),
                            'place_of_birth'    =>  ucwords(strtolower($request->place_of_birth)),
                            'date_of_birth'     =>  $request->date_of_birth,
                            'gender'            =>  $request->gender,
                            'address'           =>  $request->address,
                            'phone'             =>  $request->phone,
                            'status'            =>  $request->status,
                        );

            User::whereId($request->off_id)->update($user);
            return response()->json(['messages' => 'Petugas Berhasil Diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Petugas Gagal Diupdate']);
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
