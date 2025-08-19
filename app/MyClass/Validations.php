<?php

namespace App\MyClass;

class Validations
{
    public static function userValidation($request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'role' => 'required',
            ],
            [
                'name.required' => 'Nama tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email Sudah Digunakan',
                'email.email' => 'Email Tidak Valid',
                'password.required' => 'Password tidak boleh kosong',
                'password.confirmed' => 'Password tidak sama',
                'role.required' => 'Role tidak boleh kosong',
            ]
        );
    }

    public static function userEditValidation($request, $userId)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $userId,
                'password' => 'nullable|confirmed',
                'role' => 'required',
            ],
            [
                'name.required' => 'Nama tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.unique' => 'Email sudah digunakan',
                'email.email' => 'Email Tidak Valid',
                'password.confirmed' => 'Password tidak sama',
                'role.required' => 'Role tidak boleh kosong',
            ]
        );
    }

    public static function AccessControlDeviceValidation($request)
    {
        $request->validate([
            'device_name' => 'required',
            'ip_address' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'notes' => 'nullable',
        ],
        [
            'device_name.required' => 'nama device tidak boleh kosong',
            'ip_address.required' => 'alamat ip tidak boleh kosong',
            'port.required' => 'port tidak boleh kosong',
            'username.required' => 'username tidak boleh kosong',
            'password.required' => 'password tidak boleh kosong',
            'notes.nullable' => 'notes di isi jika diperlukan'
        ]);
    }

    public static function AccessControlDeviceEditValidation($request)
    {
        $request->validate([
            'device_name' => 'required',
            'ip_address' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'notes' => 'nullable',
        ],
        [
            'device_name.required' => 'nama device tidak boleh kosong',
            'ip_address.required' => 'alamat ip tidak boleh kosong',
            'port.required' => 'port tidak boleh kosong',
            'username.required' => 'username tidak boleh kosong',
            'password.required' => 'password tidak boleh kosong',
            'notes.nullable' => 'notes di isi jika diperlukan'
        ]);
    }

    public static function peopleValidation($request)
    {
        $request->validate([
            'name' => 'required',
            'photo'=> 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'notes' => 'nullable'
        ],
        [
            'name.required' => 'nama tidak boleh kosong',
            'photo.required' => 'photo tidak boleh kosong',
            'photo.max' =>'photo tidak boleh lebih dari 2048 px',
            'photo.image' =>'photo tidak boleh sama',
            'photo.mimes' => 'format photo harus berupa jpeg, png, jpg, dan svg',
            'notes.nullable' => 'notes di isi jika diperlukan'
        ]);
    }

    public static function peopleEditValidation($request)
    {
        $request->validate([
            'name' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'notes' => 'nullable'
        ],
        [
            'name.required' => 'nama tidak boleh kosong',
            'photo.required' => 'photo tidak boleh kosong',
            'photo.max' =>'photo tidak boleh lebih dari 2048 px',
            'photo.image' =>'photo tidak boleh sama',
            'photo.mimes' => 'format photo harus berupa jpeg, png, jpg, dan svg',
            'notes.nullable' => 'notes di isi jika diperlukan'
        ]);
    }

    public static function galleryValidation($request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,svg|max:2048',
        ],
        [
            'title.required' => 'judul tidak boleh kosong',
            'image.required' => 'image tidak boleh kosong',
            'image.max' =>'image tidak boleh lebih dari 2048 px',
            'image.mimes' => 'format image harus berupa jpeg, png, jpg, dan svg',
        ]);
    }

    public static function galleryEditValidation($request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,svg|max:2048',
        ],
        [
            'title.required' => 'judul tidak boleh kosong',
            'image.required' => 'image tidak boleh kosong',
            'image.max' =>'image tidak boleh lebih dari 2048 px',
            'image.mimes' => 'format image harus berupa jpeg, png, jpg, dan svg',
        ]);
    }
}
