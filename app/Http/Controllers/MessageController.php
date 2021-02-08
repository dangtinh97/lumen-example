<?php

namespace App\Http\Controllers;


use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        $this->validate($request,[
            'conversation_id'=>'required|exists:conversations,_id',
            'last_message_id'=>'exists:messages,_id',
        ],[
            'required' => ':attribute không được để trống',
            'conversation_id.exists'=>'conversation_id phải có trong bảng conversations',
            'last_message_id.exists'=>'message_id phải có trong bảng messages'
        ]);
        $list = $this->messageService->listMessage($request);
        return response()->json($list->toArray());
    }

    public function store(Request $request){
        $this->validate($request,[
            'take_id'=>'required|exists:users,_id',
            'content'=>'required'
        ],
        [
            'required' => ':attribute không được để trống',
            'take_id.exists'=>':attribute phải có trong bảng users'
        ]);
        $create = $this->messageService->createMessage($request);
        return response()->json($create->toArray());
    }

    public function destroy($id)
    {
        $delete = $this->messageService->deleteMessage($id);
        return response()->json($delete->toArray());
    }

    public function destroyAll($id)
    {
        $deleteAll = $this->messageService->deleteAllMessage($id);
        return response()->json($deleteAll->toArray());
    }
}


