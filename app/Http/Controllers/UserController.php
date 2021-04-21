<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    use ApiResponser;
    
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        
    }


    public function getUsers()
    {
        $users =  DB::connection('mysql')->select("Select * from tbluser");
        return $this->successResponse($users);
    }


    public function index()
    {
        $users = User::all();

        return $this->successResponse($users);
    }


    public function add(Request $request)
    {
        $rules = [

            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',

        ];


        $this->validate($request, $rules);
        $users = User::create($request->all());
        return $this->successResponse($users, Response::HTTP_CREATED);

    }


    public function show($id)
    {
        $users = User::where('userid', $id)->first();
        if($users){
            return $this->successResponse($users);
        }
    
        {
            return $this->errorResponse('User ID Does Not Exist', Response::HTTP_NOT_FOUND);

        }

    }
    
    public function update(Request $request, $id)
    {

        $rules = [

            'username' => 'max:20',
            'password' => 'max:20',
            // 'gender' => 'required|in:Male,Female',

        ];

        $this->validate($request, $rules);

        $users = User::findOrFail($id);

        $users->fill($request->all());
        
        $users->save();
        if($users){
            return $this->successResponse($users);
        }
    
        {
            return $this->errorResponse('User ID Does Not Exist', Response::HTTP_NOT_FOUND);

        }
    }

    public function delete($id)
    {
        $users = User::where('userid', $id)->first();
        if($users){
            $users->delete();
            return $this->successResponse($users);
        }
    
        {
            return $this->errorResponse('User ID Does Not Exist', Response::HTTP_NOT_FOUND);

        }

    }    
}

?>