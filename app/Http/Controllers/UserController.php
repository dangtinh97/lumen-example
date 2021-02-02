<?php
namespace App\Http\Controllers;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function create(Request $request){
        $this->validate($request,[
            'full_name'=>'required',
            'password'=>'nullable|min:8',
            'birthday'=>'required',
            'gender'=>'required',
            'email'=>'required|unique:users|email',
            'phone'=>'nullable|numeric|unique:users',
        ],[
            'full_name.required'=>'Ten khong duoc de trong',
            'password.min'=>'Mat khau phai lon hon 8 ky tu',
            'birthday.required'=>'Ngay sinh khong duoc de trong',
            'gender.required'=>'Gioi tinh khong duoc de trong',
            'email.unique'=>'Email da ton tai',
            'email.email'=>'Email chua dung dinh dang',
            'phone.numberic'=>'So dien thoai chi co cac so',
            'phone.unique'=>'Da ton tai sdt',
        ]);
        $create = $this->userService->create($request);
        return response()->json($create->toArray());
    }

    public function update(Request $request){
        $update = $this->userService->update($request);
        return response()->json($update->toArray());
    }
    public function delete(){
        $delete = $this->userService->delete();
        return response()->json($delete->toArray());
    }
    public function login(){
        $login = $this->userService->login();
        return response()->json($login->toArray());
    }
    public function logout()
    {
        $logout = $this->userService->logout();
        return response()->json($logout->toArray());
    }
    public function getUserLogin(){
        $user = $this->userService->getUserLogin();
        return response()->json($user->toArray());
    }
}