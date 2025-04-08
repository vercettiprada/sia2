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
        // Retrieve all users from the database using the connection to MySQL.
        $users = DB::connection('mysql')->select("SELECT * FROM tbl_user");
        return $this->successResponse($users);
    }

    public function index()
    {
        // Retrieve all users using the Eloquent model.
        $users = User::all();
        return $this->successResponse($users);
    }

    public function add(Request $request)
    {
        // Validation rules for adding a new user.
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
        ];

        // Validate the incoming request.
        $this->validate($request, $rules);

        // Create a new user in the database.
        $user = User::create($request->all());

        // Return the created user with a 201 status.
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        // Attempt to find the user or fail with a 404.
        $user = User::findOrFail($id);
        return $this->successResponse($user); 
    }

    public function delete($id)
    {
        // Attempt to find the user by ID.
        $user = User::findOrFail($id);
        
        // Delete the user.
        $user->delete();

        // Return success message after deletion.
        return $this->successResponse('User successfully deleted', Response::HTTP_OK);
    }
}
