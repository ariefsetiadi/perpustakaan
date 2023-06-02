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
            $category->status       =   $request->status;
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
                                'status'        =>  $request->status,
                            );

            Category::whereId($request->category_id)->update($category);

            return response()->json(['messages' => 'Kategori Berhasil Diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => 'Kategori Gagal Diupdate']);
        }
    }
}
