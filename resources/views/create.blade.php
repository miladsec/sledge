@extends('vendor.sledge.layouts.app')
@section('title')
    {{ $title ?? '' }}
@stop

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="mt-5">

                    @foreach($ff as $f)
                            {!! $f !!}
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@stop
