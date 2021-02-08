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
            'birthday'=>'required|date',
            'gender'=>'required',
            'email'=>'required|unique:users|email',
            'phone'=>'nullable|digits:10|unique:users',
        ],[
            'required'=>':attribute không đúng định dạng',
            'min'=>':attribute phải lớn hơn 8 ký tự',
            'date'=>':attribute không đúng định dạng',
            'email'=>':attribute không đúng định dạng',
            'digits'=>':attribute phải là số và dài 10 ký tự',
            'unique'=>':attribute đã tồn tại'
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
