<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use File;
use PDF;
use Validator;

use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Member';

        if(request()->ajax()) {
            return datatables()->of(Member::orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<a href="' . route('member.print', $data->id) . '" class="btn btn-success ml-2" title="Print" target="_blank"><i class="fas fa-print"></i></a>';
                    $button .=   '<a href="' . route('member.show', $data->id) . '" class="btn btn-info ml-2" title="Detail"><i class="fas fa-eye"></i></a>';
                    $button .=   '<a href="' . route('member.edit', $data->id) . '" class="btn btn-warning mx-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $button .=  '<button type="button" id="'.$data->id.'" class="btnDelete btn btn-danger" title="Hapus"><i class="fas fa-eraser"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.member.index', $data);
    }

    public function create()
    {
        $data['title']  =   'Tambah Member';
        $data['button'] =   'Simpan';
        $data['member'] =   '';

        return view('data.member.form', $data);
    }

    public function store(Request $request)
    {
        $member_code    =   strtoupper($request->member_code);
        $image          =   $request->image;
        $image_name     =   NULL;

        $validate   =   Validator::make($request->all(), [
                            'member_code'       =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/||unique:members,member_code',
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'place_of_birth'    =>  'required|max:255',
                            'date_of_birth'     =>  'required|date',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:10,15|unique:members,phone',
                            'image'             =>  'required|max:5120',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'member_code.required'      =>  'ID Member wajib diisi',
                            'member_code.max'           =>  'ID Member maksimal 10 karakter',
                            'member_code.regex'         =>  'ID Member hanya boleh huruf dan angka',
                            'member_code.unique'        =>  'ID Member sudah digunakan',
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
                            'image.required'            =>  'Foto wajib diupload',
                            'image.max'                 =>  'Foto maksimal 5 Mb',
                            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        if($image != NULL) {
            $image_name =   'members_' . $member_code . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/members'), $image_name);
        }

        $member                   =   new Member;
        $member->member_code      =   $member_code;
        $member->fullname         =   ucwords(strtolower($request->fullname));
        $member->place_of_birth   =   ucwords(strtolower($request->place_of_birth));
        $member->date_of_birth    =   $request->date_of_birth;
        $member->gender           =   $request->gender;
        $member->address          =   $request->address;
        $member->phone            =   $request->phone;
        $member->image            =   $image_name;
        $member->save();

        return response()->json(['success' => 'Member Berhasil Disimpan']);
    }

    public function show($id)
    {
        $data['title']  =   'Detail Member';
        $data['member'] =   Member::findOrFail($id);

        return view('data.member.detail', $data);
    }

    public function edit($id)
    {
        $data['title']  =   'Edit Member';
        $data['button'] =   'Update';
        $data['member'] =   Member::findOrFail($id);

        return view('data.member.form', $data);
    }

    public function update(Request $request)
    {
        $member     =   Member::findOrFail($request->member_id);
        $image_name =   $member->image;

        $member_code    =   strtoupper($request->member_code);
        $image          =   $request->image;

        $validate   =   Validator::make($request->all(), [
                            'member_code'       =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/||unique:members,member_code,' . $request->member_id,
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'place_of_birth'    =>  'required|max:255',
                            'date_of_birth'     =>  'required|date',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:10,15|unique:members,phone,' . $request->member_id,
                            'image'             =>  'max:5120',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'member_code.required'      =>  'ID Member wajib diisi',
                            'member_code.max'           =>  'ID Member maksimal 20 karakter',
                            'member_code.regex'         =>  'ID Member hanya boleh huruf dan angka',
                            'member_code.unique'        =>  'ID Member sudah digunakan',
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
            File::delete('uploads/members/' . $member->image);

            $image_name =   'members_' . $member_code . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/members'), $image_name);
        }

        $member =   array(
                    'member_code'       =>  $request->member_code,
                    'fullname'          =>  ucwords(strtolower($request->fullname)),
                    'place_of_birth'    =>  ucwords(strtolower($request->place_of_birth)),
                    'date_of_birth'     =>  $request->date_of_birth,
                    'gender'            =>  $request->gender,
                    'address'           =>  $request->address,
                    'phone'             =>  $request->phone,
                    'image'             =>  $image_name,
                );

        Member::whereId($request->member_id)->update($member);

        return response()->json(['success' => 'Member Berhasil Diupdate']);
    }

    public function destroy($id)
    {
        $member =   Member::findOrFail($id);
        $member->delete();

        return response()->json(['success' => 'Member Berhasil Dihapus']);
    }

    public function trash()
    {
        $data['title']  =   'Trash Member';

        if(request()->ajax()) {
            return datatables()->of(Member::onlyTrashed()->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<button type="button" id="'.$data->id.'" class="btnRestore btn btn-success" title="Pulihkan"><i class="fas fa-trash-restore"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.member.trash', $data);
    }

    public function restore($id)
    {
        $member =   Member::withTrashed()->findOrFail($id);
        $member->restore();

        return response()->json(['success' => 'Member Berhasil Dipulihkan']);
    }

    public function print($id)
    {
        $member =   Member::findOrFail($id);

        $customPaper    =   array(0, 0, 155.91, 240.94);
        $pdf = PDF::loadview('data.member.print', ['member' => $member])->setPaper($customPaper, 'landscape');
	    return $pdf->stream();
    }
}
