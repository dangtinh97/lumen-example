<?php

namespace App\Http\Controllers;


use App\Services\ExampleService;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $exampleService;

    public function __construct(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
    }

    public function store(Request $request){
        $this->validate($request,[
            'full_name'=>'required',
            'password'=>'required',
            'email'=>'nullable|email',
            'mobile'=>'required'
        ]);
        $create= $this->exampleService->createUser($request);
        return response()->json($create->toArray());
    }

    //
}
