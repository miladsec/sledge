@extends('sledge::layouts.app')

@section('navbar')
    @include('sledge::layouts.sections.navbar')
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
                            @foreach($sledge['form']['header'] as $header)
                                {{ $header }}
                            @endforeach

                            <div class="row">
                                @foreach($sledge['form']['body'] as $body)
                                    <div class="{{ $body->data->col ?? '' }}">
                                        {{ $body }}
                                    </div>
                                @endforeach
                            </div>

                            @foreach($sledge['form']['footer'] as $footer)
                                {{ $footer }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    {!! (isset($sledge['script'])) ? $sledge['script'] : '' !!}
@endsection
