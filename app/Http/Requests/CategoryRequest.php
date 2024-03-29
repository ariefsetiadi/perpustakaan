<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name'      =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/|unique:categories,name,' . $this->category_id,
            'status'    =>  'required|in:0,1'
        ];
    }

    public function messages()
    {
        return [
            'name.required'     =>  'Nama Kategori wajib diisi',
            'name.max'          =>  'Nama Kategori maksimal 255 karakter',
            'name.regex'        =>  'Nama Kategori hanya boleh huruf, angka dan spasi',
            'name.unique'       =>  'Nama Kategori sudah digunakan',
            'status.required'   =>  'Status wajib dipilih',
            'status.in'         =>  'Status tidak valid',
        ];
    }
}
