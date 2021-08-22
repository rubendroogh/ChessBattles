<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use \PChess\Chess\Chess;
use \PChess\Chess\Move;

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
        $game->gamestatefen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'; // FEN notation of the starting position plus setup
        $game->possiblemoves = '{["a3", "a4", "b3", "b4", "c3", "c4", "d3", "d4", "e3", "e4", "f3", "f4", "g3", "g4", "h3", "h4", "Na3", "Nc3", "Nf3", "Nh3"]}'; // Possible moves in a starting position
        $game->secretwhite = $this->str_random(16);
        $game->secretblack = $this->str_random(16);
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

        $color = $this->isAllowed($request->secret, $game);

        if ($color == false) {
            return response()->json([
                'success' => '0',
                'reason' => 'Secret incorrect.'
            ]);
        }
        elseif ($color = 'white') {
            $game->whitestarted = true;
        }
        elseif ($color = 'black') {
            $game->blackstarted = true;
        }

        if ($game->whitestarted == true && $game->blackstarted == true) {
            $game->isactive = true;
        }

        if ($game->isactive == false) {
            return response()->json([
                'success' => '0',
                'reason' => 'Game hasn\'t started yet.',
                'isActive' => '0'
            ]);
        }

        $move = $request->move;
        $event = 'game-move-'.$game->id;
        
        $pusher->trigger('gamemoves', $event, $move);

        $prevPosition = $game->gamestatefen;

        $gameSimulated = new Chess($game->gamestatefen);
        $gameSimulated->move($request->move);
        $possibleMoves = array_map(fn($move) => $move->san, $gameSimulated->moves());
        $game->gamestatefen = $gameSimulated->fen();
        $game->save();
        
        return response()->json([
            'success' => '1',
            'prevPosition' => $prevPosition,
            'currentPosition' => $game->gamestatefen,
            'possibleMoves' => $possibleMoves,
            'color' => $color,
            'isActive' => '1'
        ]);
    }

    private function get_pusher_object(){
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

    // Needs refactoring
    private function str_random(int $length){
        $random = str_shuffle('abcdtfghjklmnopqrseuvwxyzAOHCDEFGQJKLMNIPBRSTUVWXYZ234987650!$%^&!$%^&');
        $str_random = substr($random, 0, $length);

        return $str_random;
    }

    // Returns the color of the player, or false if not allowed
    private function isAllowed($secret, $game) {
        if ($secret == $game->secretblack){
            return 'black';
        }

        if ($secret == $game->secretwhite){
            return 'white';
        }

        return false;
    }
}
