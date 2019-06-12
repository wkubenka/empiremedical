<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class LeaderboardController extends Controller
{

    /**
     *  Return ten leaders
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::select('name', 'wins', 'games')->orderBy('wins', 'desc')->paginate(10);
        return response()->json($users);
    }
}
