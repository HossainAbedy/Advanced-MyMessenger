<?php

namespace App\Http\Controllers;
use Auth;
use DB;
use App\Message;
use App\User;
use App\Friendship;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function getall(){
        // get all users except the authenticated one
        $users = User::where('id', '!=', auth()->id())->paginate(10);
        return response()->json($users);
    }

    // public function getMessagesfor($id){
    //     $messages = Message::where('from',$id)->orWhere('to',$id)->get();
    //     return response()->json($messages);
    // }

    public function get()
    {
        // get all friends
        $contacts = array();
        
        $friends = array();
		

		$f1 = Friendship::where('status', 1)
					->where('requester', auth()->id())
					->get();

		foreach($f1 as $friendship):
			array_push($friends, \App\User::find($friendship->user_requested));
		endforeach;


		$friends2 = array();
		
		$f2 = Friendship::where('status', 1)
					->where('user_requested', auth()->id())
					->get();

		foreach($f2 as $friendship):
			array_push($friends2, \App\User::find($friendship->requester));
		endforeach;

        $contacts=array_merge($friends, $friends2);
        
        // $contacts = array_map(function($array){
        //     return (object)$array;
        // }, $contacts);
        // $contacts = User::where('id', '!=', auth()->id())->get();
        // get a collection of items where sender_id is the user who sent us a message
        // and messages_count is the number of unread messages we have from him
        $unreadIds = Message::select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->where('read', false)
            ->groupBy('from')
            ->get();
        // add an unread key to each contact with the count of unread messages
        
        $contacts = collect($contacts);
        $contacts = $contacts->map(function($contact) use ($unreadIds) {
        $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();
        $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;
        return $contact;
        });
        return response()->json($contacts);
    }
    public function getMessagesFor($id)
    {
        // mark all messages with the selected contact as read
        Message::where('from', $id)->where('to', auth()->id())->update(['read' => true]);
        // get all messages between the authenticated user and the selected user
            $messages = Message::where(function($q) use ($id) {
                $q->where('from', auth()->id());
                $q->where('to', $id);
            })->orWhere(function($q) use ($id) {
                $q->where('from', $id);
                $q->where('to', auth()->id());
            })
            ->get();
            return response()->json($messages);
    }
    public function send(Request $request)
    {
        if(request()->has('file')){
            $filename = request('file')->store('public/img');
            $subject = $filename ;
            $search = 'public/img' ;
            $trimmed = str_replace($search, '', $subject) ;
            $message=Message::create([
                'from' => auth()->id(),
                'to' => $request->contact_id,
                'image' => $trimmed,
                'text' => $request->text
            ]);
        }else{
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text,
            'replyTextId' => $request->replyTextId,
            'replyText' => $request->replyText
        ]);
        }
        broadcast(new NewMessage($message));
        return response()->json($message);
    }
}
