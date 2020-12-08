<div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb p-0 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        @foreach($data as $d)
                            @if(in_array( 'active', $d))
                                <li class="breadcrumb-item {{ $d[1] }}">{{ $d[0] }}
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $d[1] }}">{{ $d[0] }}</a>
                                </li>
                            @endif
                        @endforeach



                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
