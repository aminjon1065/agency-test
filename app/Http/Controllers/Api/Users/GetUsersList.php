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
        $data = [];
        foreach ($users as $key => $item) {
            $data[$key]['value'] = $item['id'];
            $data[$key]['label'] = $item['name'];
        }
        $allUsers = [...$data];
        return response()->json($data);
    }
}
