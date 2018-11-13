@extends('layouts.app')

@section('title', 'Index')

@section('content')

    <div class="row text-center m-top-5">
        <h1 id="title">NASA API Search</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">

            <form class="form" method="post">

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search" name="q" value="{{ $search }}"/>


                        {{ csrf_field() }}
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group col-xs-offset-2 col-sm-offset-2 col-md-offset-2">
                    <div class="checkbox-inline">
                        <label><input name="image" type="checkbox"> Images</label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input name="audio" type="checkbox"> Audio</label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input name="video" type="checkbox"> Video</label>
                    </div>
                </div>

            </form>
        </div>

    </div>

    @isset($items)
    <div class="row m-top-5">

        @foreach($items as $item)

            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 nasa-item thumbnail">
                <a href="{{route('asset', ['nasaId' => $item['data'][0]['nasa_id']])}}">
                    @if ($item['data'][0]['media_type'] == 'image')
                        <img class="nasa-img" src="{{ $item['links'][0]['href'] }}" title="{{ $item['data'][0]['title'] }}" alt="{{ $item['data'][0]['title'] }}"/>
                    @elseif($item['data'][0]['media_type'] == 'audio')
                        <div class="icon-container text-center" title="{{ $item['data'][0]['title'] }}">
                            <i class="fa fa-volume-up fa-5x" ></i>
                        </div>
                    @else
                        <div class="icon-container text-center" title="{{ $item['data'][0]['title'] }}">
                            <i class="fa fa-video-camera fa-5x" ></i>
                        </div>
                    @endif
                </a>
            </div>

        @endforeach

    </div>
    @endisset

@endsection