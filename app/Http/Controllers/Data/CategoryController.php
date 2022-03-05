<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Kategori';

        if(request()->ajax()) {
            return datatables()->of(Category::orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<button type="button" id="'.$data->id.'" class="btnEdit btn btn-warning mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></button>';
                    $button .=  '<button type="button" id="'.$data->id.'" class="btnDelete btn btn-danger ml-1" title="Hapus"><i class="fas fa-eraser"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.category.index', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $name   =   ucwords(strtolower($request->name));

        $validate   =   Validator::make($request->all(), [
                            'name'          =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/|unique:categories,name',
                        ],
                        [
                            'name.required' =>  'Nama Kategori wajib diisi',
                            'name.max'      =>  'Nama Kategori maksimal 255 karakter',
                            'name.regex'    =>  'Nama Kategori hanya boleh huruf, angka dan spasi',
                            'name.unique'   =>  'Nama Kategori sudah digunakan',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $category               =   new Category;
        $category->name         =   $name;
        $category->description  =   $request->description;
        $category->save();

        return response()->json(['success' => 'Kategori Berhasil Disimpan']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category   =   Category::findOrFail($id);

        return response()->json(['data' => $category]);
    }

    public function update(Request $request)
    {
        $name   =   ucwords(strtolower($request->name));

        $validate   =   Validator::make($request->all(), [
                            'name'          =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/|unique:categories,name,' . $request->category_id,
                        ],
                        [
                            'name.required' =>  'Nama Kategori wajib diisi',
                            'name.max'      =>  'Nama Kategori maksimal 255 karakter',
                            'name.regex'    =>  'Nama Kategori hanya boleh huruf, angka dan spasi',
                            'name.unique'   =>  'Nama Kategori sudah digunakan',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $category   =   array(
                            'name'          =>  $name,
                            'description'   =>  $request->description,
                        );

        Category::whereId($request->category_id)->update($category);

        return response()->json(['success' => 'Kategori Berhasil Disimpan']);
    }

    public function destroy($id)
    {
        $category   =   Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => 'Kategori Berhasil Dihapus']);
    }

    public function trash()
    {
        $data['title']  =   'Trash Kategori';

        if(request()->ajax()) {
            return datatables()->of(Category::onlyTrashed()->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<button type="button" id="'.$data->id.'" class="btnRestore btn btn-success" title="Pulihkan"><i class="fas fa-trash-restore"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.category.trash', $data);
    }

    public function restore($id)
    {
        $category   =   Category::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json(['success' => 'Kategori Berhasil Dipulihkan']);
    }
}
