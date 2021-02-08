<?php


namespace App\Services;


use App\Http\Responses\ResponseSuccess;
use App\Models\Message;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;

class MessageService
{
    protected $messageRepository;
    protected $conversationRepository;

    public function __construct(MessageRepository $messageRepository, ConversationRepository $conversationRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
    }

    public function listMessage($request)
    {
        $listMessage = $this->messageRepository->getMessagesOfConversation($request->get('conversation_id'), $request->get('last_message_id'));
        return (new ResponseSuccess($listMessage, 'List message:'));
    }

    public function createMessage($request)
    {
        $conversation = $this->conversationRepository->getIdConversation($request->get('take_id'));
        if ($conversation == [])
        {
            $this->conversationRepository->create([
                'conversation_id'=>new ObjectId(),
                'admin_id'=>new ObjectId(Auth::id()),
                'join_id'=>new ObjectId($request->get('take_id'))
            ]);
        }
        $create = $this->messageRepository->create([
                'conversation_id'=>new ObjectId($conversation[0]->_id),
                'send_id'=>new ObjectId(Auth::id()),
                'take_id'=>new ObjectId($request->get('take_id')),
                'content'=>$request->get('content')
        ]);
        return (new ResponseSuccess($create,'Tạo message thành công!'));
    }

    public function deleteMessage($id)
    {
        $message = $this->messageRepository->findById($id);
        $message->delete();
        return (new ResponseSuccess($message,'Xóa tin nhắn thành công!'));
    }

    public function deleteAllMessage($id)
    {
        $listMessage = $this->messageRepository->findWhere(['conversation_id'=>new ObjectId($id)]);
        foreach ($listMessage as $message)
        {
            $message->delete();
        }
        return (new ResponseSuccess($listMessage, 'Xóa toàn bộ tin nhắn của cuộc hội thoại thành công'));
    }
}



