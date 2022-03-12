<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class AuthController extends ApiBaseController
{
    use AuthenticatesUsers;

    public function username()
    {
        return 'cpf';
    }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8'
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors());       
    //     }

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password)
    //      ]);

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()
    //         ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    // }

    public function login(Request $request)
    {
        if (!Auth::attempt(['cpf' => $request->cpf, 'password' => $request->senha]))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('cpf', $request['cpf'])->firstOrFail();

        if(!$user->ativo) {
            return response()
                ->json(['message' => 'Usuário inativo.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}