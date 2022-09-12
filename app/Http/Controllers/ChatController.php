<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function searchUsers(Request $request){
        $users = User::where('email','!=',$request->user()['email'])->get();
        return response()->json($users, 200);
    }
    public function createChat(User $chatWith){
        $userCreator= auth()->user();
        $userInvited=$chatWith;
        $chatCreate = $userCreator->$chats()->wherehas('users',function($q)use($userInvited){});
        dd($userCreator,$userInvited);
    }
}
