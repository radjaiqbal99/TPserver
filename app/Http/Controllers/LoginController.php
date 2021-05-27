<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required']
        ]);
        // $checkLogin=User::where('username',$request->username)->where('password')
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $checkLogin = Auth::check();
        if (!$checkLogin) {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $response = [
                    'data' => [
                        'info' => 'Selamat Datang',
                        'premission' => 'true',

                    ]
                ];
                return response()->json($response, Response::HTTP_OK);
            } else {
                $response = [
                    'message' => ['Username Atau Password Salah'],
                ];
                return response()->json($response, Response::HTTP_NOT_FOUND);
            }
        } else {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $response = [
                    'data' => [
                        'info' => 'Selamat Datang',
                        'premission' => 'true',

                    ]
                ];
                return response()->json($response, Response::HTTP_OK);
            } else {
                $response = [
                    'message' => ['Username Atau Password Salah'],
                ];
                return response()->json($response, Response::HTTP_NOT_FOUND);
            }
        }
    }
    public function update(Request $request)
    {
        $update = User::where("username", "admin")->first();
        $update->update([
            "password" => bcrypt($request->new)
        ]);
    }
}
