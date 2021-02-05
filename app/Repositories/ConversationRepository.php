<?php


namespace App\Repositories;


use App\Models\Conversation;
use Jenssegers\Mongodb\Eloquent\Model;

class ConversationRepository extends BaseRepository
{
    public function __construct(Conversation $model)
    {
        $this->model = $model;
    }

    public function getIdConversation($idTake)
    {
        return $this->model->getIdConversation($idTake);
    }
}

