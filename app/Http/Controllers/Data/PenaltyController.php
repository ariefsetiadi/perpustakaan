<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PenaltyRequest;

use App\Models\Penalty;

class PenaltyController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Denda';

        if (request()->ajax()) {
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

    public function update(PenaltyRequest $request)
    {
        try {
            $name   =   ucwords(strtolower($request->name));

            $penalty    =   array(
                                'name'  =>  $name,
                                'value' =>  $request->value,
                            );

            Penalty::whereId($request->penalty_id)->update($penalty);

            return response()->json(['messages' => 'Denda Berhasil Diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Denda Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        //
    }
}
