<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    //return all roles
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        $role = Role::create($request->only('name'));

        $role->permissions()->attach($request->input('permissions'));

        // load will display the permission when created
        return response(new RoleResource($role->load('permissions')), Response::HTTP_CREATED);
    }

    public function show($id)
    {
        // show users with their repective roles
        return new RoleResource(Role::with('permissions')->find($id));
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        
        // update the name of the requested field
        $role->update($request->only('name'));

        // sync we see the current value of the permissions and update them accordingly
        // sync will detach the old roles and attach the new roles
        $role->permissions()->sync($request->input('permissions'));

        return \response(new RoleResource($role->load('permissions')), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Role::destroy($id);

        return \response(null, Response::HTTP_NO_CONTENT);
    }
}