<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['active'] = true;
        $user = User::create($request->toArray());

        $userRole = Role::where('name', 'USER')->firstOrFail();
        $user->roles()->attach($userRole);

        $token = $user->createToken('api')->accessToken;
        $response = ['token' => $token, 'user' => $user->load('roles')];
        return response($response, 200);
    }

    public function registerMechanic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'regon' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['active'] = false;
        $user = User::create($request->toArray());

        Firm::create($request->toArray());

        $userRole = Role::where('name', 'USER')->firstOrFail();
        $user->roles()->attach($userRole);
        $userRole = Role::where('name', 'MECHANIC')->firstOrFail();
        $user->roles()->attach($userRole);

        $response = ['result' => true];
        return response($response, 200);
    }

    public function login(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {

            if (!$user->active) {
                $response = ['message' => 'User is inactive'];
                return response($response, 422);
            }

            if (Hash::check($request->password, $user->password)) {
                $this->scope = $user->getMaxRole();
                $token = $user->createToken('api', [$this->scope])->accessToken;
                $response = ['token' => $token, 'user' => $user->load('roles')];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


}
