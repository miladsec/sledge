@extends('layouts.panel.app')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            {{   $cc['navLink'] }}
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->

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
                <!-- Dashboard Ecommerce ends -->
            </div>
        </div>
    </div>
@endsection
