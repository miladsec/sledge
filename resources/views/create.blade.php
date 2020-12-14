@extends('layouts.portal.app')
@section('navLink')
    {{   $cc['navLink'] }}
@endsection
@section('content')
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="بستن">
                <span aria-hidden="true">×</span>
            </button>
            @foreach ($errors->all() as $error)
                <div class="d-flex align-items-center">
                    <i class="bx bx-error"></i>
                    <span>{{$error}}</span>
                </div>
            @endforeach
        </div>
    @endif
    <section class="input-validation">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title ?? '' }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @foreach($cc['data']['header'] as $h)
                                {{ $h }}
                            @endforeach

                            <div class="row">
                                @foreach($cc['data']['body'] as $k=>$b)
                                    <div class="col-md-6">
                                        {{ $b }}
                                    </div>
                                @endforeach
                            </div>

                            @foreach($cc['data']['footer'] as $f)
                                {{ $f }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('my_js')
    {!! (isset($customScript)) ? $customScript : '' !!}
@endsection
