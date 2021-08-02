@extends('layouts.app')

@section('content')
    <div class="flex justify-content-center align-items-center" style="height: 100vh">
        <div class="home-boards-wrap p-3 shadow-sm" style="background: white;">
            <h2 class="mt-2">Current games</h2>
            <hr>
            <div class="home-boards flex flex-row flex-wrap" id="boards">
                @foreach ($games as $game)
                <a class="home-board" href="/game/{{ $game->id }}">
                    <chessboard class="mx-3 mt-3 mb-0" fen="{{ $game->gamestatefen }}"></chessboard>
                    <p class="text-center p-0 m-0">{{ $game->playerwhite }} vs {{ $game->playerblack }}</p>
                </a>
                @endforeach
            </div>
        </div>
        <div class="p-3 shadow-sm ml-3" style="background: white;">
            <h2>Battle your own AIs!</h2>
            <hr>
            {{ Form::open(array('route' => 'createGame')) }}
                @csrf

                <div class="form-group">
                    <label for="playerwhite">Enter opponent 1's name (white)</label>
                    <input type="text" class="form-control" id="playerwhite" name="playerwhite">
                </div>
                <div class="form-group">
                    <label for="playerblack">Enter opponent 2's name (black)</label>
                    <input type="text" class="form-control" id="playerblack" name="playerblack">
                </div>
                <button type="submit" class="btn btn-primary" >Begin!</button>
            {{ Form::close() }}
        </div>
    </div>

    <script src="/js/app.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@endsection
        
