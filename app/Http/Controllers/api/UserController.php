<?php

namespace App\Http\Controllers\api;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List of all users (including admin users)
     */
    public function getAllUsers(UserService $userService)
    {
        return $userService->getAllUsersService();
    }
}
