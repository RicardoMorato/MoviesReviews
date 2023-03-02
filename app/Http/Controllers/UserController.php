<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resources
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::all();

        return response()->json([
            'data'=>$users
        ], 200);
    }

    /**
     * Store a newly created resource in storage
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // TO-DO: CREATE UNIT TEST FOR USER VALIDATOR
        $validator = $this->validateUserCreation($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        if (User::create($validator->validate())) {
            return response()
                    ->json(['data'=>$validator->validate()], 201);
        }

        return response()
                ->json(['data'=>null, 'error'=>'An odd error ocurred'], 500);
    }

    /**
     * Display the specific resource
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id) {
        return response()
                ->json(['data'=> User::findOrFail($id)], 200);
    }

    /**
     * Update the specific resource
     * 
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request) {
        $validator = $this->validateUserUpdate($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->save();

        return response()
                ->json(['data'=>$user], 200);
    }

    /**
     * Remove the specified reousrce from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return response()
                    ->json(['data'=>'User Deleted'], 200);
        }

        return response()
                ->json(['data'=>null, 'error'=>'An odd error ocurred'], 500);
    }

    public function validateUserCreation(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|min:8|max:255',
        ]);
    }

    public function validateUserUpdate(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns',
        ]);  
    }
}
