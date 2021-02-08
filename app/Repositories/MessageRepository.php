<?php


namespace App\Repositories;


use App\Models\Message;
use Jenssegers\Mongodb\Eloquent\Model;

class MessageRepository extends BaseRepository
{
    public function __construct(Message $model)
    {
        $this->model = $model;
    }

    public function getMessagesOfConversation($conversationId, $lastMessageId)
    {
        return $this->model->getData($conversationId, $lastMessageId);
    }

}


