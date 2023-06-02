<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;

use File;
use PDF;

use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Member';

        if (request()->ajax()) {
            return datatables()->of(Member::orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<a href="' . route('member.print', $data->id) . '" class="btn btn-success ml-2" title="Print" target="_blank"><i class="fas fa-print"></i></a>';
                    $button .=   '<a href="' . route('member.show', $data->id) . '" class="btn btn-info ml-2" title="Detail"><i class="fas fa-eye"></i></a>';
                    $button .=   '<a href="' . route('member.edit', $data->id) . '" class="btn btn-warning mx-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';

                    return $button;
                })->editColumn('status', function($data) {
                    if ($data->status == TRUE) {
                        $status =  '<h5><span class="badge badge-success">AKTIF</span></h5>';
                    } else {
                        $status =  '<h5><span class="badge badge-danger">NONAKTIF</span></h5>';
                    }

                    return $status;
                })->rawColumns(['action', 'status'])->addIndexColumn()->make(true);
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

    public function store(MemberRequest $request)
    {
        try {
            $member_code    =   strtoupper($request->member_code);
            $path           =   'uploads/members';
            $image          =   $request->image;
    
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0775, true, true);
            }

            $image_name =   'member_' . $member_code . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($path), $image_name);

            $member                 =   new Member;
            $member->member_code    =   $member_code;
            $member->fullname       =   ucwords(strtolower($request->fullname));
            $member->place_of_birth =   ucwords(strtolower($request->place_of_birth));
            $member->date_of_birth  =   $request->date_of_birth;
            $member->gender         =   $request->gender;
            $member->address        =   $request->address;
            $member->phone          =   $request->phone;
            $member->image          =   $image_name;
            $member->status         =   $request->status;
            $member->save();

            return response()->json(['messages' => 'Member Berhasil Disimpan']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Member Gagal Disimpan']);
        }
    }

    public function show($id)
    {
        try {
            $data['title']  =   'Detail Member';
            $data['member'] =   Member::findOrFail($id);

            return view('data.member.detail', $data);
        } catch (\Throwable $th) {
            return redirect()->route('member.index');
        }
    }

    public function edit($id)
    {
        try {
            $data['title']  =   'Edit Member';
            $data['button'] =   'Update';
            $data['member'] =   Member::findOrFail($id);

            return view('data.member.form', $data);
        } catch (\Throwable $th) {
            return redirect()->route('member.index');
        }
    }

    public function update(MemberRequest $request)
    {
        try {
            $member     =   Member::findOrFail($request->member_id);

            $path       =   'uploads/members';
            $image_name =   $member->image;

            $member_code    =   strtoupper($request->member_code);
            $image          =   $request->image;

            if ($image != NULL) {
                File::delete('uploads/members/' . $image_name);

                $image_name =   'member_' . $member_code . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($path), $image_name);
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
                            'status'            =>  $request->status,
                        );
    
            Member::whereId($request->member_id)->update($member);
            return response()->json(['messages' => 'Member Berhasil Diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Member Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        try {
                $member =   Member::findOrFail($id);
            $member->delete();

            return response()->json(['messages' => 'Member Berhasil Dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Member Gagal Dihapus']);
        }
    }

    public function trash()
    {
        $data['title']  =   'Trash Member';

        if (request()->ajax()) {
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
        try {
            $member =   Member::withTrashed()->findOrFail($id);
            $member->restore();

            return response()->json(['messages' => 'Member Berhasil Dipulihkan']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Member Gagal Dipulihkan']);
        }
    }

    public function print($id)
    {
        $member =   Member::findOrFail($id);

        $customPaper    =   array(0, 0, 155.91, 240.94);
        $pdf = PDF::loadview('data.member.print', ['member' => $member])->setPaper($customPaper, 'landscape');
	    return $pdf->stream();
    }
}
