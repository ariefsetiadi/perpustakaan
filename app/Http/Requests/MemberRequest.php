<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'member_code'       =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/||unique:members,member_code,' . $this->member_id,
            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
            'place_of_birth'    =>  'required|max:255',
            'date_of_birth'     =>  'required|date',
            'gender'            =>  'required|in:Laki-Laki,Perempuan',
            'address'           =>  'required',
            'phone'             =>  'required|digits_between:10,15|unique:members,phone,' . $this->member_id,
            'image'             =>  'required_without:member_id|max:5120',
            'image.*'           =>  'mimes:jpg,jpeg,png',
            'status'            =>  'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'member_code.required'      =>  'ID Member wajib diisi',
            'member_code.max'           =>  'ID Member maksimal 10 karakter',
            'member_code.regex'         =>  'ID Member hanya boleh huruf dan angka',
            'member_code.unique'        =>  'ID Member sudah digunakan',
            'fullname.required'         =>  'Nama Lengkap wajib diisi',
            'fullname.max'              =>  'Nama Lengkap maksimal 255 karakter',
            'fullname.regex'            =>  'Nama Lengkap hanya boleh huruf dan spasi',
            'place_of_birth.required'   =>  'Tempat Lahir wajib diisi',
            'place_of_birth.max'        =>  'Tempat Lahir maksimal 255 karakter',
            'date_of_birth.required'    =>  'Tanggal Lahir wajib diisi',
            'date_of_birth.date'        =>  'Tanggal Lahir tidak valid',
            'gender.required'           =>  'Jenis Kelamin wajib dipilih',
            'gender.in'                 =>  'Jenis Kelamin tidak valid',
            'address.required'          =>  'Alamat wajib diisi',
            'phone.required'            =>  'Telepon wajib diisi',
            'phone.digits_between'      =>  'Telepon 10 - 15 angka',
            'phone.unique'              =>  'Telepon sudah digunakan',
            'image.required_without'    =>  'Foto wajib diupload',
            'image.max'                 =>  'Foto maksimal 5 Mb',
            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png',
            'status.required'           =>  'Status wajib dipilih',
            'status.in'                 =>  'Status tidak valid',
        ];
    }
}
