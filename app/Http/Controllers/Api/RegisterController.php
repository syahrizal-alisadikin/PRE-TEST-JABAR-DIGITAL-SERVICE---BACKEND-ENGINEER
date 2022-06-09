<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //set validation
        $validator = Validator::make(
            $request->all(),
            [
                'nik'      => 'required|integer|digits:16|unique:wargas',
                'roles'     => 'required',
                'password'  => 'required|min:6'
            ],
            [
                'nik.required'     => 'NIK wajib diisi',
                'nik.integer'      => 'NIK harus berupa angka',
                'nik.digits'          => 'NIK 16 digit',

                'nik.unique'       => 'NIK sudah terdaftar',
                'roles.required'   => 'Roles wajib diisi',
                'password.required' => 'Password wajib diisi',
                'password.min'     => 'Password minimal 6 digit'
            ]
        );

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = Warga::create([
            'nik'      => $request->nik,
            'roles'     => $request->roles,
            'password'  => bcrypt($request->password)
        ]);

        //return response JSON user is created
        if ($user) {
            return response()->json([
                'success' => true,
                'user'    => $user,
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }
}
