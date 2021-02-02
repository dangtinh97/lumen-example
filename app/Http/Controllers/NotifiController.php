<?php
namespace App\Http\Controllers;
use App\Services\NotifiService;
use Illuminate\Http\Request;

class NotifiController extends Controller{
    protected $notifiService;
    public function __construct(NotifiService $notifiService)
    {
        $this->notifiService = $notifiService;
    }
    public function create(Request $request,$id_post){
        $create = $this->notifiService->create($request,$id_post);
        return response()->json($create->toArray());
    }

    public function listNotification(){
        $listNotification = $this->notifiService->listNotification();
        return response()->json($listNotification->toArray());
    }

    public function delete($id_notification){
        $delete = $this->notifiService->delete($id_notification);
        return response()->json($delete->toArray());
    }
}