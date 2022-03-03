<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Penalty;

class PenaltyController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Denda';

        if(request()->ajax()) {
            return datatables()->of(Penalty::orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<button type="button" id="'.$data->id.'" class="btnEdit btn btn-warning" title="Edit"><i class="fas fa-pencil-alt"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.penalty.index', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $penalty    =   Penalty::findOrFail($id);

        return response()->json(['data' => $penalty]);
    }

    public function update(Request $request)
    {
        $name   =   ucwords(strtolower($request->name));

        $validate   =   Validator::make($request->all(), [
                            'name'  =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/|unique:penalties,name,' . $request->penalty_id,
                            'value' =>  'required|digits_between:1,11',
                        ],
                        [
                            'name.required'         =>  'Jenis Denda wajib diisi',
                            'name.max'              =>  'Jenis Denda maksimal 255 karakter',
                            'name.regex'            =>  'Jenis Denda hanya boleh huruf, angka dan spasi',
                            'name.unique'           =>  'Jenis Denda sudah digunakan',
                            'value.required'        =>  'Biaya Denda wajib diisi',
                            'value.digits_between'  =>  'Biaya Denda wajib angka, 1 - 11 angka',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $penalty    =   array(
                            'name'  =>  ucwords(strtolower($request->name)),
                            'value' =>  $request->value,
                        );

        Penalty::whereId($request->penalty_id)->update($penalty);

        return response()->json(['success' => 'Denda Berhasil Diupdate']);
    }

    public function destroy($id)
    {
        //
    }
}
