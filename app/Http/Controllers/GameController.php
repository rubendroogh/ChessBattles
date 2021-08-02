<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Game;

class GameController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function home()
    {
        $games = Game::all()->take(8);
        return view('home', ['games' => $games]);
    }

    public function createForm()
    {
        return view('creategame');
    }

    public function create(Request $request)
    {
        $game = new Game;
        $game->playerwhite = $request->input('playerwhite');
        $game->playerblack = $request->input('playerblack');
        $game->gamestatefen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR'; // FEN notation of the starting position
        $game->save();

        return redirect()->route('game', ['id' => $game->id]);
    }

    public function game($id)
    {
        $game = Game::where('id', $id)->first();
        return view('board', ['game' => $game]);
    }

    public function move($id, Request $request)
    {
        $game = Game::where('id', $id)->first();
        
        dd($game);
    }
}
