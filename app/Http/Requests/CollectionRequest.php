<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'   =>  'required|exists:categories,id',
            'code'          =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/|unique:collections,code,' . $this->collection_id,
            'name'          =>  'required|max:255|unique:collections,name,' . $this->collection_id,
            'price'         =>  'required|digits_between:1,11',
            'register_date' =>  'required|date',
            'image'         =>  'max:5120',
            'image.*'       =>  'mimes:jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required'      =>  'Kategori wajib dipilih',
            'category_id.exists'        =>  'Kategori tidak ditemukan',
            'code.required'             =>  'ID Koleksi wajib diisi',
            'code.max'                  =>  'ID Koleksi maksimal 10 karakter',
            'code.regex'                =>  'ID Koleksi hanya boleh huruf dan angka',
            'code.unique'               =>  'ID Koleksi sudah digunakan',
            'name.required'             =>  'Nama Koleksi wajib diisi',
            'name.max'                  =>  'Nama Koleksi maksimal 255 karakter',
            'name.unique'               =>  'Nama Koleksi sudah digunakan',
            'price.required'            =>  'Harga wajib diisi',
            'price.digits_between'      =>  'Harga wajib angka, 1 - 11 angka',
            'register_date.required'    =>  'Tanggal Terdaftar wajib diisi',
            'register_date.date'        =>  'Tanggal Terdaftar tidak valid',
            'image.max'                 =>  'Foto maksimal 5 Mb',
            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png'
        ];
    }
}
