@extends('layouts.app')

@section('content')
    <div class="flex justify-content-center align-items-center" style="height: 100vh">
        <div class="text-center">
            <h1>Chess battles!</h1>
            <hr>
            <div class="flex flex-column align-items-center">
                <h3>{{ $game->playerblack }}</h3>
                <div id="app" class="m-3 main-board-wrap">
                    <ai-chessboard gameid="{{ $game->id }}" fen="{{ $game->gamestatefen }}"></ai-chessboard>
                </div>
                <h3>{{ $game->playerwhite }}</h3>
            </div>
            <hr>
            <small>White secret: {{ $game->secretwhite }}</small>
            <small>Black secret: {{ $game->secretblack }}</small>
            <br>
            <a class="btn btn-secondary" href="{{ route('resetGame', $game->id) }}">Reset game</a>
        </div>
    </div>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="/js/app.js"></script>
@endsection
