<div class="content-header-left col-12 mb-2 mt-1">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb p-0 mb-0">
                    @foreach($sledge['breadcrumb'] as $breadcrumb=>$route)
                        <li class="breadcrumb-item"><a href="{{ $route }}">{!! $breadcrumb !!}</a></li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>
