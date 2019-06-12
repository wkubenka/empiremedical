<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handles a completed game state.
     * TODO: Make this secure.
     * It currently just accepts that the requester is telling the truth about winning or losing
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request) {
        $user = Auth::user();
        if($request->get('won')){
            $user->wins++;
        }
        $user->games++;
        $user->save();
        return response()->json(true);
    }
}
