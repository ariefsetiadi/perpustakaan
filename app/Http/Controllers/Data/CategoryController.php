<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

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

    public function store(CategoryRequest $request)
    {
        try {
            $name   =   ucwords(strtolower($request->name));

            $category               =   new Category;
            $category->name         =   $name;
            $category->description  =   $request->description;
            $category->save();

            return response()->json(['messages' => 'Kategori Berhasil Disimpan']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Kategori Gagal Disimpan']);
        }
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

    public function update(CategoryRequest $request)
    {
        try {
            $name   =   ucwords(strtolower($request->name));

            $category   =   array(
                                'name'          =>  $name,
                                'description'   =>  $request->description,
                            );

            Category::whereId($request->category_id)->update($category);

            return response()->json(['messages' => 'Kategori Berhasil Diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Kategori Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        try {
            $category   =   Category::findOrFail($id);
            $category->delete();

            return response()->json(['messages' => 'Kategori Berhasil Dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Kategori Gagal Dihapus']);
        }
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
        try {
            $category   =   Category::withTrashed()->findOrFail($id);
            $category->restore();

            return response()->json(['messages' => 'Kategori Berhasil Dipulihkan']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Kategori Gagal Dipulihkan']);
        }
    }
}
