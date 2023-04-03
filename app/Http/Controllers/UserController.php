<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected User $user;

    // jwt : https://dev.to/meherulsust/how-to-build-a-jwt-authenticated-api-with-lumen-831-171o

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->post(), [
            'username' => 'required|unique:user,username,except,id_user|min:3|max:50|alpha_num',
            'password' => 'required|min:6|max:30',
            'name' => 'required|min:3|max:100',
        ]);

        if($validation->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'ERROR PARAMETER',
                'data' => $validation->errors()->all()
            ], 400);
        }

        $insert = $this->user->create(
            [
                'username' => $request->post('username'),
                'password' => Hash::make($request->post('password')),
                'name' => $request->post('name')
            ]
        );

        if($insert) {
            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => 'Successfully Register',
                'data' => [
                    'username' => $request->post('username'),
                    'name' => $request->post('name'),
                ]
            ], 201);
        } else {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Register Failed',
                'data' => null
            ], 200);
        }
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->post(), [
            'username' => 'required|min:3|max:50|alpha_num',
            'password' => 'required|min:6|max:30',
        ]);

        if($validation->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'ERROR PARAMETER',
                'data' => $validation->errors()->all()
            ], 400);
        }

        $user = $this->user->where('username', $request->post('username'))->first();
        if(!$user) {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Login Failed',
                'data' => null
            ], 200);
        }

        $isValidPassword = Hash::check($request->post('password'), $user->password);
        if (!$isValidPassword) {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Login Failed',
                'data' => null
            ], 200);
        }

        if($isValidPassword) {
            $token = Auth::login($user);
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Login Success',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'username' => $user->username,
                        'name' => $user->name,
                    ]
                ]
            ], 200);
        } else {
            return response()->json([
                'code' => 200,
                'success' => false,
                'message' => 'Login Failed',
                'data' => null
            ], 200);
        }
    }
}
