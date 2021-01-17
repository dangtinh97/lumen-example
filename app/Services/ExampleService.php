<?php


namespace App\Services;


use App\Http\Responses\ResponseSuccess;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class ExampleService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser($request){
        $create = $this->userRepository->create([
           'mobile'=>$request->get('mobile'),
           'full_name'=>$request->get('full_name'),
           'password'=>Hash::make($request->get('password')),
           'email'=>$request->get('email','')
        ]);
        return (new ResponseSuccess($create,'Tạo tài khoản thành công!'));
    }
}
