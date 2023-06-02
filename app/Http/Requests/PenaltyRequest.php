<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenaltyRequest extends FormRequest
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
            'name'      =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/|unique:penalties,name,' . $this->penalty_id,
            'value'     =>  'required|digits_between:1,11',
            'status'    =>  'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         =>  'Jenis Denda wajib diisi',
            'name.max'              =>  'Jenis Denda maksimal 255 karakter',
            'name.regex'            =>  'Jenis Denda hanya boleh huruf, angka dan spasi',
            'name.unique'           =>  'Jenis Denda sudah digunakan',
            'value.required'        =>  'Biaya Denda wajib diisi',
            'value.digits_between'  =>  'Biaya Denda wajib angka, 1 - 11 angka',
            'status.required'       =>  'Status wajib dipilih',
            'status.in'             =>  'Status tidak valid'
        ];
    }
}
