<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
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
        $validator = Validator::make($request->all(), [
            'nik'     => 'required|integer|digits:16',
            'password'  => 'required'
        ], [
            'nik.required'     => 'NIK wajib diisi',
            'nik.integer'      => 'NIK harus berupa angka',
            'nik.digits'          => 'NIK 16 digit',

            'password.required' => 'Password wajib diisi'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //get credentials from request
        $credentials = $request->only('nik', 'password');

        //if auth failed
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Nik atau Password Anda salah'
            ], 401);
        }

        //if auth success
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),
            'token'   => $token
        ], 200);
    }

    public function warga()
    {
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user()
        ], 200);
    }

    public function products()
    {
        $product = Http::get('https://60c18de74f7e880017dbfd51.mockapi.io/api/v1/jabar-digital-services/product');
        $hasil = [];
        foreach ($product->json() as $value) {

            $query =  "IDR_USD";
            $currency = Http::get('https://free.currconv.com/api/v7/convert?q=IDR_USD&compact=ultra&apiKey=0ab1c59977d21b49276d');
            $obj = json_decode($currency, true);

            $amount = $value["price"];
            $val = floatval($obj["IDR_USD"]);
            $total = $val * $amount;

            $total = $val * $amount;
            $value["price"] = "Rp " . $value["price"];
            $value["price USD"] = $total;


            $hasil[] =  $value;
        }
        return response()->json([
            'success' => true,
            'data'    => $hasil
        ], 200);
    }

    public function productsAdmin()
    {
        if (auth()->guard('api')->user()->roles == "admin") {
            $product = Http::get('https://60c18de74f7e880017dbfd51.mockapi.io/api/v1/jabar-digital-services/product');

            $hasil = [];
            foreach ($product->json() as $value) {

                $value["price"] = "Rp " . $value["price"];

                $hasil[] =  $value;
            }
            $collection = collect($hasil)->sortBy('price');

            return $collection;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses'
            ], 401);
        }
    }

    public function claim()
    {
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user()
        ], 200);
    }
}
