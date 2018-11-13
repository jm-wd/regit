@extends('layouts.app')

@section('title', 'Asset')

@section('content')

    <div class="row text-center m-top-5">
        <h1 id="title">{{ $asset['info']['title'] }}</h1>
    </div>

    <div class="row m-top-5">
        <div class="col-xs-12">
            <p>{{ $asset['info']['description'] }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            @if($asset['info']['media_type'] == 'image')
                <img class="img-responsive" src="{{ $asset['assetSource'] }}" title="{{ $asset['info']['title'] }}" alt="{{ $asset['info']['title'] }}"/>
            @elseif($asset['info']['media_type'] == 'audio')
                <div class="col-xs-offset-3 col-md-offset-5 m-top-5">
                    <audio controls>
                        <source src="{{ $asset['assetSource'] }}" type="audio/mpeg">
                        Your browser does not support audio playback.
                    </audio>
                </div>
            @else
                <video class="img-responsive" controls>
                    <source src="{{ $asset['assetSource'] }}" type="video/mp4">
                    Your browser does not support video playback.
                </video>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <h3>Keywords:</h3>
            @foreach($asset['info']['keywords'] as $keyword)
                <button type="button" class="btn btn-info text-capitalize">{{ $keyword }}</button>
            @endforeach
            <h3>Created:</h3>
            <p>{{ \Carbon\Carbon::parse($asset['info']['date_created'])->format('d/m/Y')}}</p>
        </div>
    </div>

@endsection