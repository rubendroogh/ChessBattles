<?php

namespace App\Http\Controllers;

use Pusher\Pusher;

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
        $game->possiblemoves = '{["a3", "a4", "b3", "b4", "c3", "c4", "d3", "d4", "e3", "e4", "f3", "f4", "g3", "g4", "h3", "h4", "Na3", "Nc3", "Nf3", "Nh3"]}'; // Possible moves in a starting position
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
        $pusher = $this->get_pusher_object();
        $game = Game::where('id', $id)->first();
        $possiblemovesjson = json_decode($game->possiblemoves, true);

        $move = $request->move;
        $event = 'game-move-'.$game->id;
        
        $pusher->trigger('gamemoves', $event, $move);
        
        return response()->json(['success' => '1', 'currentposition' => $game->gamestatefen, 'possiblemoves' => $possiblemovesjson]);
    }

    public function get_pusher_object(){
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        return $pusher;
    }
}
