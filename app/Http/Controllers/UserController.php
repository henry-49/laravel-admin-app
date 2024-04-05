<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function index()
    {
        // return User::all();
        // return User::with('role')->paginate();
        // \Gate::authorize('view', 'users');
        $this->authorize('view', 'users');

        return UserResource::collection(User::with('role')->paginate());
    }

    // store single user
    public function store(UserCreateRequest $request)
    {
        // authorize user to edit
        $this->authorize('edit', 'users');

        //create user
        $user = User::create(
            $request->only('first_name', 'last_name', 'email')
            + ['password' => Hash::make(1234)]
        );

        // to return a single user, use new UserResource($user)
        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    // show single user
    public function show($id)
    {
        //find user and get the role
        // return User::with('role')->find($id);

        // authorize user to view
        $this->authorize('view', 'users');

         // to return a single user, use new UserResource($user)
        return new UserResource(User::with('role')->find($id));

    }

    public function update(UserUpdateRequest $request, $id)
    {
        // authorize user to edit
        $this->authorize('edit', 'users');

        //update user
        $user = User::find($id);
        // by using only we dont change the first name
        // if first_name is no provided it will show last_name and email
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));


        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        //delete user
        // $user = User::find($id);
        // $user->delete();
        
        $this->authorize('edit', 'users');

        User::destroy($id);

        return \response(null, Response::HTTP_NO_CONTENT);

   }
}