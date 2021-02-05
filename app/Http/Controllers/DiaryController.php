<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Services\DiaryService;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $diaryService;

    public function __construct(DiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }

    public function index()
    {
        $list = $this->diaryService->listDiary();
        return response()->json($list->toArray());
    }

    public function destroy(Request $request, $id)
    {
        $request = new Request(array_merge($request->all(), ['diary_id'=>$id]));
        $this->validate($request, [
            'diary_id'=>'required|exists:diaries,_id'
        ],[
            'exists'=>':attribute phải có trong bảng diaries'
        ]);
        $delete = $this->diaryService->deleteDiary($id);
        return response()->json($delete->toArray());
    }

    public function destroyAll()
    {
        $deleteAll = $this->diaryService->deleteAllDiary();
        return response()->json($deleteAll->toArray());
    }
}


