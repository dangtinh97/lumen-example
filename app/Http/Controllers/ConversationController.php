<?php

namespace App\Http\Controllers;


use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function index()
    {
        $list = $this->conversationService->listConversation();
        return response()->json($list->toArray());
    }

}



