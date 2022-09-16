<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use function React\Promise\Stream\first;

class ChatController extends Controller
{
    public function stringToArrayNumber($chatsUser){
        $peopleMatchString= "";
        foreach ($chatsUser as $key => $chatUser) {
            $peopleMatchString .= $key == 0 ? "$chatUser[people]" :",$chatUser[people]" ;
        }
        $peopleMatchArray = strlen($peopleMatchString) > 0 ? explode(",",$peopleMatchString) : [];
        $peopleNumber = [];
        foreach($peopleMatchArray as $personMatch){
            array_push($peopleNumber,intval($personMatch));
        }
        return $peopleNumber;
    }
    public function searchUsers(){
        $usersRandom = User::where('id','!=',auth()->id())->limit(12)->inRandomOrder()->get();
        $chatsUser=User::where('id','=',auth()->id())->first()->chats;
        $idUsersChat = $this->stringToArrayNumber($chatsUser);
        $usersValidate = [];
        foreach($usersRandom as $userRandom){
            if (in_array($userRandom['id'],$idUsersChat)) {
                continue;
            }
            array_push($usersValidate,$userRandom);
        };
        return $usersValidate;
    }
    public function createChat(Request $request){
        $data = $request->usersInvited;
        $Chat = Chat::create([
                'people' => implode(',',$data)
        ]);
        $Chat->users()->attach(auth()->id());
        return response()->json("Chat creado correactamente", 200);
    }
    public function showChat(Chat $chatId){
        abort_unless($chatId->users->contains(auth()->id()),403);
        $allMessages = Message::where('chat_id','=' ,[$chatId['id']])->get();
        return response()->json($allMessages, 200);
    }
    public function updateChat(Chat $chatId, Request $request){
        abort_unless($chatId->users->contains(auth()->id()),403);
        $updateChat = Message::create([
            'content'=>$request['message'],
            'user_id'=>auth()->id(),
            'chat_id'=>$chatId['id']
        ]);
        $updateChat->save();
        return response()->json('Message ok', 200);
    }
}