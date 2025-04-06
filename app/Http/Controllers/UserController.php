<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Traits\ApiResponser;
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
        $users = DB::connection('mysql')->select("SELECT * FROM tbl_user");
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

        $user = User::create($request->all());


        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    public function show($id)
    {
    $user = User::findOrFail($id);
    return $this->successResponse($user); 
       /*
        $user = User::where('id', $id)->first();

        if ($user) {
            return $this->successResponse($user);
        } else {
            return $this->errorResponse('User Id Does Not Exist', Response::HTTP_NOT_FOUND);
        }
        */
    }
public function delete($id)
{

    $user = User::findOrFail($id);
    $user->delete();
    return $this->errorResponse('User ID Does Not Exist', Response::HTTP_NOT_FOUND);
    

    }
}