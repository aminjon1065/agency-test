<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUsersList extends Controller
{
    public function getUsers()
    {
        $users = User::all(['id', 'name']);
        return response()->json($users, 200);
    }
}
