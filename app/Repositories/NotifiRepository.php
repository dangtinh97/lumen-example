<?php
namespace App\Repositories;

use App\Models\Notification;
use Jenssegers\Mongodb\Eloquent\Model;

class NotifiRepository extends BaseRepository {
     public function __construct(Notification $model)
     {
//         parent::__construct($model);
         $this->model = $model;
     }
     public function getUser(){
         return $this->model->getUser();
     }
 }