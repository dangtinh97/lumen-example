<?php


namespace App\Services;


use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Models\Diary;
use App\Repositories\DiaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;

class DiaryService
{
    protected $diaryRepository;

    public function __construct(DiaryRepository $diaryRepository)
    {
        $this->diaryRepository = $diaryRepository;
    }

    public function listDiary()
    {
        $listDiary = $this->diaryRepository->diaryOfUser(new ObjectId(Auth::id()));
        return (new ResponseSuccess($listDiary, 'List diary:'));
    }

    public function deleteDiary($id)
    {
        $diary = $this->diaryRepository->findById($id);
        $userIdDiary = $diary->user_id->__toString();
        if ($userIdDiary !== Auth::id()) return (new ResponseError(500, 'Xóa nhật ký không thành công'));
        $diary->delete();
        return (new ResponseSuccess($diary, 'Xóa nhật ký thành công'));
    }

    public function deleteAllDiary()
    {
        $listDiary = $this->diaryRepository->findWhere(['user_id'=>new ObjectId(Auth::id())]);
        foreach ($listDiary as $diary)
        {
            $diary->delete();
        }
        return (new ResponseSuccess($listDiary, 'Xóa toàn bộ nhật ký thành công'));
    }
}


