<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficerRequest extends FormRequest
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
            'officer_id'        =>  'required|max:20|regex:/^[a-zA-Z0-9]*$/||unique:users,officer_id,' . $this->off_id,
            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
            'place_of_birth'    =>  'required|max:255',
            'date_of_birth'     =>  'required|date',
            'gender'            =>  'required|in:Laki-Laki,Perempuan',
            'address'           =>  'required',
            'phone'             =>  'required|digits_between:10,15|unique:users,phone,' . $this->off_id,
            'image'             =>  'max:5120|mimes:jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'officer_id.required'       =>  'Nomor Petugas wajib diisi',
            'officer_id.max'            =>  'Nomor Petugas maksimal 20 karakter',
            'officer_id.regex'          =>  'Nomor Petugas hanya boleh huruf dan spasi',
            'officer_id.unique'         =>  'Nomor Petugas sudah digunakan',
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
            'image.max'                 =>  'Foto maksimal 5 Mb',
            'image.mimes'               =>  'Foto hanya boleh format jpg, jpeg, atau png'
        ];
    }
}
