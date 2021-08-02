@extends('layouts.app')

@section('content')
    <div class="flex justify-content-center align-items-center" style="height: 100vh">
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
</html>