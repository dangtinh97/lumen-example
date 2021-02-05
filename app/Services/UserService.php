<?php

namespace App\Services;

use App\Http\Responses\StatusCode;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ResponseError;
use App\Http\Responses\ResponseSuccess;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($request)
    {
        $create = $this->userRepository->create([
            'full_name' => $request->get('full_name'),
            'password' => Hash::make($request->get('password')),
            'birthday' => date('Y-m-d', strtotime($request->get('birthday'))),
            'gender' => $request->get('gender'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'avatar'=> "abc.jpg"

        ]);
        return (new ResponseSuccess($create, 'Tạo tài khoản thành công!'));
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return (new ResponseError(StatusCode::BAD_REQUEST, 'Email hoac password khong dung!'));
        }

        return (new ResponseSuccess(['token' => $token], 'Dang nhap thanh cong!'));
    }
    public function logout()
    {
        Auth::logout();
        return (new ResponseSuccess(StatusCode::SUCCESS, 'Dang xuat thanh cong'));
    }
    public function update($request)
    {

        $update = $this->userRepository->findById(new ObjectId(Auth::id()));
//        dd($update);
//        $id = new ObjectId($id);
//        dd($this->id_user)
//            dd($update);

//        $update = $update->update([
//            'full_name' => $request->get('full_name'),
//            'password' => Hash::make($request->get('password')),
//            'birthday' => $request->get('birthday'),
//            'gender' => $request->get('gender'),
//            'email' => $request->get('email'),
//            'phone' => $request->get('phone'),
//            'avatar'=>$request->get('avatar'),
//        ]);
        $update->full_name = $request->get('full_name');
//        dd($update->full_name);
        $update->password = Hash::make($request->get('password'));
        $update->birthday = $request->get('birthday');
        $update->gender = $request->get('gender');
        $update->phone = $request->get('phone');
        $update->save();

//        $update->avatar = $request->file('avatar');
//        if (($request->get('avatar'))) {
//            // Lấy tên file
//            $file_name = $request->file('avatar')->getClientOriginalName();
//            // Lưu file vào thư mục upload với tên là biến $filename
//            $request->file('avatar')->move('upload', $file_name);
//        }
        $extension = pathinfo($request->get('avatar'), PATHINFO_EXTENSION);
//        $extension = strtolower($extension);
//        dd($request->get('avatar'));
        $arr_extension = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $arr_extension)) {
            $update->avatar = '';
//                    $request->get('avatar')== '';
            return (new ResponseError(StatusCode::BAD_REQUEST, 'Update that bai!'));
        } else {
            $update->avatar = $request->get('avatar');
            $update->save();
            return (new ResponseSuccess($update, 'Update thành công!'));
        }

    }

    public function delete()
    {

//        $update = $this->userRepository->findById($id);
        $delete = $this->userRepository->findById(new ObjectId(Auth::id()));
//            dd($delete);
        if ($delete) {
            $delete->delete();
            return (new ResponseSuccess($delete, 'Xoa thanh cong!'));
        }
        return (new ResponseError(StatusCode::BAD_REQUEST, 'Xoa that bai'));
    }


//    protected function respondWithToken($token)
//    {
//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'bearer',
//            'expires_in' => Auth::factory()->getTTL() * 60
//        ]);
//    }

    public function getUserLogin()
    {
        $user = Auth::user();
        return (new ResponseSuccess($user, 'Thong tin user'));
    }
}
