<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => '|required|string|min:6|confirmed',
            'region' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'rank' => 'required|string',
            'signature' => 'required|image',
            'avatar' => 'required|image',
//            'signature' => 'required|mimes:jpeg,bmp,png,pdf,docx,doc,xlsx, xls'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray(),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'rank' => $request->rank,
            $signature = $request->file('signature'),
            $signatureName = $request->name . '-' . time() . '.' . $signature->getClientOriginalExtension(),
            $signaturePath = public_path('/signatures'),
            $signature->move($signaturePath, $signatureName),
            'signature' => $signatureName,
            $image = $request->file('avatar'),
            $imageName = time() . '-' . $image->getClientOriginalName(),
            $imagePath = public_path('/avatars'),
            $image->move($imagePath, $imageName),
            'avatar' => $imageName,
            'department' => $request->department,
            'region' => $request->region,
        ];
        $user = User::create($data);
        if ($user) {
//                $token = $user->createToken('__register_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Успешно зарегистрировано',
//                    'data' => 'token: ' . $token . ' ',
//            'data' => $token,
            ], 201);
        }
//            $user->assignRole('user');
        return response()->json([
            'status' => 'error',
            'message' => 'Непредвиденная ошибка'
        ], 422);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email',
            'password' => '|required|string|min:6',
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не правильный E-mail или пароль'
            ], 401);
        }

        $token = auth()->user()->createToken('__sign_token')->plainTextToken;
        $user = $this->isAuth($token)->only(['name', 'email', 'region', 'position', 'department', 'rank', 'avatar']);
        return response()->json([
            'status' => 'success',
            'message' => 'Успешно вошли в систему',
            'token' => $token,
            'data' => $user
        ], 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Успешно вышли',
        ], 204);
    }

    public function isAuth()
    {
        $userAllInfo = \auth()->user();
        $user['name'] = $userAllInfo['name'];
        $user['email'] = $userAllInfo['email'];
        $user['rank'] = $userAllInfo['rank'];
        $user['position'] = $userAllInfo['position'];
        $user['signature'] = $userAllInfo['signature'];
        $user['avatar'] = $userAllInfo['avatar'];
        return $user;
    }
}
