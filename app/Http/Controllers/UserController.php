<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUser($id)
    {
        return Role::find($id)->users;
    }

    public function getNotAvailableUsers(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $users = User::where('active', false)->get();
        return response(['users' => $users], 200);
    }

    public function userAccountDecision(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|int',
            'decision' => 'required|bool',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where('id', $request['userId'])->first();
        if ($user == null) {
            return response(['message' => 'User not found'], 404);
        }

        $toName = $user->name . ' ' . $user->surname;
        $toEmail = $user->email;

        $data = array('name' => 'Zespół Cars Serwis');

        if ($request['decision']) {
            $user->active = true;
            $user->save();

            Mail::send('mail', $data, function ($message) use ($toName, $toEmail) {
                $message->to($toEmail, $toName)->subject('Komunikat ze strony Cars Serwis');
                $message->from('carsserwispl@gmail.com', 'Zespół Cars Serwis');
            });

            return response(['message' => 'User account has been activated successfully'], 200);
        }

        $user->delete();

        Mail::send('disable-mail', $data, function ($message) use ($toName, $toEmail) {
            $message->to($toEmail, $toName)->subject('Komunikat ze strony Cars Serwis');
            $message->from('carsserwispl@gmail.com', 'Zespół Cars Serwis');
        });

        return response(['message' => 'User account has been deleted successfully'], 200);
    }
}
