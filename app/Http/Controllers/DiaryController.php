<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\DiaryService;
class DiaryController extends Controller{
    protected $diaryService;
    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }
    public function create(Request $request,$id_post){
        $create = $this->diaryService->create($request,$id_post);
        return response()->json($create->toArray());
    }

    public function listDiary(){
        $listNotification = $this->diaryService->listDiary();
        return response()->json($listNotification->toArray());
    }

    public function delete($id_diary){
        $delete = $this->diaryService->delete($id_diary);
        return response()->json($delete->toArray());
    }
}