<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use File;
use Validator;

use App\Models\Category;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $data['title']  =   'Data Koleksi';

        if(request()->ajax()) {
            return datatables()->of(Collection::with(['category'])->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =   '<button type="button" id="'.$data->id.'" class="btnStock btn btn-success" title="Edit Stok"><i class="fas fa-boxes"></i></button>';
                    $button .=   '<a href="' . route('collection.show', $data->id) . '" class="btn btn-info ml-2" title="Detail"><i class="fas fa-eye"></i></a>';
                    $button .=   '<a href="' . route('collection.edit', $data->id) . '" class="btn btn-warning mx-2" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                    $button .=  '<button type="button" id="'.$data->id.'" class="btnDelete btn btn-danger" title="Hapus"><i class="fas fa-eraser"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.collection.index', $data);
    }

    public function create()
    {
        $data['title']      =   'Tambah Koleksi';
        $data['button']     =   'Simpan';
        $data['category']   =   Category::orderBy('name')->get();
        $data['collection'] =   '';

        return view('data.collection.form', $data);
    }

    public function store(Request $request)
    {
        $code       =   strtoupper($request->code);
        $image      =   $request->image;
        $image_name =   NULL;

        $validate   =   Validator::make($request->all(), [
                            'category_id'   =>  'required|exists:categories,id',
                            'code'          =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/|unique:collections,code',
                            'name'          =>  'required|max:255|unique:collections,name',
                            'register_date' =>  'required|date',
                            'image'         =>  'max:5120',
                            'image.*'       =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'category_id.required'      =>  'Kategori wajib dipilih',
                            'category_id.exists'        =>  'Kategori tidak ditemukan',
                            'code.required'             =>  'ID Koleksi wajib diisi',
                            'code.max'                  =>  'ID Koleksi maksimal 10 karakter',
                            'code.regex'                =>  'ID Koleksi hanya boleh huruf dan angka',
                            'code.unique'               =>  'ID Koleksi sudah digunakan',
                            'name.required'             =>  'Nama Koleksi wajib diisi',
                            'name.max'                  =>  'Nama Koleksi maksimal 255 karakter',
                            'name.unique'               =>  'Nama Koleksi sudah digunakan',
                            'register_date.required'    =>  'Tanggal Terdaftar wajib diisi',
                            'register_date.date'        =>  'Tanggal Terdaftar tidak valid',
                            'image.max'                 =>  'Foto maksimal 5 Mb',
                            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        if($image != NULL) {
            $image_name =   'collections_' . $code . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/collections'), $image_name);
        }

        $collection                 =   new Collection;
        $collection->category_id    =   $request->category_id;
        $collection->code           =   $code;
        $collection->name           =   ucwords(strtolower($request->name));
        $collection->register_date  =   $request->register_date;
        $collection->description    =   $request->description;
        $collection->image          =   $image_name;
        $collection->save();

        return response()->json(['success' => 'Koleksi Berhasil Disimpan']);
    }

    public function editStock($id)
    {
        $collection =   Collection::findOrFail($id);

        return response()->json(['data' => $collection]);
    }

    public function updateStock(Request $request)
    {
        $validate   =   Validator::make($request->all(), [
                            'stock' =>  'required|integer|between:1,1000',
                        ],
                        [
                            'stock.required'        =>  'Stok wajib diisi',
                            'stock.integer'         =>  'Stok wajib angka',
                            'stock.between'         =>  'Stok antara 1 - 1000',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $data   =   Collection::findOrFail($request->collection_id);

        $collection =   array(
                            'stock' =>  $request->stock + $data->stock,
                        );

        Collection::whereId($request->collection_id)->update($collection);

        return response()->json(['success' => 'Stok Berhasil Diupdate']);
    }

    public function show($id)
    {
        $data['title']      =   'Detail Koleksi';
        $data['collection'] =   Collection::with(['category'])->findOrFail($id);

        return view('data.collection.detail', $data);
    }

    public function edit($id)
    {
        $data['title']      =   'Edit Koleksi';
        $data['button']     =   'Update';
        $data['collection'] =   Collection::with('category')->findOrFail($id);
        $data['category']   =   Category::get();

        return view('data.collection.form', $data);
    }

    public function update(Request $request)
    {
        $collection =   Collection::findOrFail($request->collection_id);
        $image_name =   $collection->image;

        $code       =   strtoupper($request->code);
        $image      =   $request->image;

        $validate   =   Validator::make($request->all(), [
                            'category_id'   =>  'required|exists:categories,id',
                            'code'          =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/|unique:collections,code,' . $request->collection_id,
                            'name'          =>  'required|max:255|unique:collections,name,' . $request->collection_id,
                            'register_date' =>  'required|date',
                            'image'         =>  'max:5120',
                            'image.*'       =>  'mimes:jpg,jpeg,png',
                        ],
                        [
                            'category_id.required'      =>  'Kategori wajib dipilih',
                            'category_id.exists'        =>  'Kategori tidak ditemukan',
                            'code.required'             =>  'ID Koleksi wajib diisi',
                            'code.max'                  =>  'ID Koleksi maksimal 10 karakter',
                            'code.regex'                =>  'ID Koleksi hanya boleh huruf dan angka',
                            'code.unique'               =>  'ID Koleksi sudah digunakan',
                            'name.required'             =>  'Nama Koleksi wajib diisi',
                            'name.max'                  =>  'Nama Koleksi maksimal 255 karakter',
                            'name.unique'               =>  'Nama Koleksi sudah digunakan',
                            'register_date.required'    =>  'Tanggal Terdaftar wajib diisi',
                            'register_date.date'        =>  'Tanggal Terdaftar tidak valid',
                            'image.max'                 =>  'Foto maksimal 5 Mb',
                            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png',
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        if($image != NULL) {
            File::delete('uploads/collections/' . $collection->image);

            $image_name =   'collections_' . $code . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/collections'), $image_name);
        }

        $collection =   array(
                            'category_id'    =>   $request->category_id,
                            'code'           =>   $code,
                            'name'           =>   ucwords(strtolower($request->name)),
                            'register_date'  =>   $request->register_date,
                            'description'    =>   $request->description,
                            'image'          =>   $image_name,
                        );

        Collection::whereId($request->collection_id)->update($collection);

        return response()->json(['success' => 'Koleksi Berhasil Diupdate']);
    }

    public function destroy($id)
    {
        $collection =   Collection::findOrFail($id);
        $collection->delete();

        return response()->json(['success' => 'Koleksi Berhasil Dihapus']);
    }

    public function trash(Type $var = null)
    {
        $data['title']  =   'Trash Koleksi';

        if(request()->ajax()) {
            return datatables()->of(Collection::with(['category'])->onlyTrashed()->orderBy('created_at', 'desc')->get())
                ->addColumn('action', function($data) {
                    $button =  '<button type="button" id="'.$data->id.'" class="btnRestore btn btn-success" title="Pulihkan"><i class="fas fa-trash-restore"></i></button>';

                    return $button;
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('data.collection.trash', $data);
    }

    public function restore($id)
    {
        $collection =   Collection::withTrashed()->findOrFail($id);
        $collection->restore();

        return response()->json(['success' => 'Koleksi Berhasil Dipulihkan']);
    }
}
