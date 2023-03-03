<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Login a user, creating a new access token
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $validator = $this->validateLogin($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'error' => 'The provided credentials are incorrect.',
                'data' => $user->password,
            ]);
        }

        $token = $user->createToken($request->email)->plainTextToken;

        return response()
                ->json(['data' => $token], 200);
    }

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
        $validator = $this->validateUserCreation($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $user = User::create($validator->validate());
        $user->password = Hash::make($request->password);
        $user->save();

        if ($user) {
            return response()
                    ->json(['data'=>$user], 201);
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

        if (!Gate::allows('update-user', $id)) {
            return response()
                    ->json(['error' => 'Forbidden operation, you cannot update another user', 'data' => null], 403);
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return response()
                ->json(['data'=>$user], 200);
    }

    /**
     * Remove the specified resource from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) {
        if (!Gate::allows('delete-user', $id)) {
            return response()
                    ->json(['error' => 'Forbidden operation, you cannot delete another user', 'data' => null], 403);
        }

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

    public function validateLogin(Request $request) {
        return Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|min:8|max:255',
        ]);
    }
}
