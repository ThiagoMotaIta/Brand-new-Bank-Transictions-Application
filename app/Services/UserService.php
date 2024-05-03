<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;

class UserService {

	public function getAllUsersService() {
        try {
            $userList = User::get();
    
            return response()->json([
                "message" => "User list",
                "users" => $userList,
            ], 200);
        } catch (Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 405);
        }

	}

}
