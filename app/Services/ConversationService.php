<?php


namespace App\Services;


use App\Http\Responses\ResponseSuccess;
use App\Models\Conversation;
use App\Repositories\ConversationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;

class ConversationService
{
    protected $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function listConversation()
    {
        $match = [
            'join_id'=>new ObjectId(Auth::id()),
            'admin_id'=>new ObjectId(Auth::id())
        ];
        $listConversation = $this->conversationRepository->findByCondition($match);
        return (new ResponseSuccess($listConversation,'List conversation:'));
    }
}


