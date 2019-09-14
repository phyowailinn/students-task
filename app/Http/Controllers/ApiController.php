<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Resources\User as UserResource;
use App\User;
use Validator;

class ApiController extends Controller
{
    public function index() {
    	return new UserCollection(User::paginate());
    }

    public function show($id) {
    	return new UserResource(User::find($id));
    }

    public function update (Request $request, $id) {
    	$rules = [
	        'name' => 'required|string|max:255',
	        'phone' => 'required',
	        'gender' => 'required',
	        'university' => 'required',
	        'city' => 'required',
	    ];
	   
	    if ($request->email) {	    	
	       $rules['email'] = ['required|string|email|max:255|unique:users'];
	    }
	    if ($request->password) {	    	
	        $rules['password'] = ['required|string|min:6|confirmed'];
	    }

	    $validator = Validator::make($request->all(), $rules);

	    if ($validator->fails())
	    {
	        return response(['errors'=>$validator->errors()->all()], 422);
	    }

	    $user = User::find($id);
	    $user->name = $request->name;
	    if($request->email) $user->email = $request->email;
	    $user->phone = $request->phone;
	    $user->gender = $request->gender;
	    $user->university = $request->university;
	    $user->city = $request->city;
	    if($request->password) $user->password = Hash::make($request->password);
	    $user->save();

	    return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
	}

    public function destroy($id) {
    	return response()->json(['success' => true], 200);
    }
}
