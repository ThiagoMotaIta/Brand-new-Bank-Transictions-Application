<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;

class UserService {

	public function getAllUsersService() {
 
        $userList = User::get();

        return response()->json([
            "message" => "User list",
            "users" => $userList,
        ], 200);

	}

}