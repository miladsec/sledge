<div>
    <ol>
        @foreach($sledge['breadcrumb'] as $breadcrumb=>$route)
            <li><a href="{{ $route }}">{!! $breadcrumb !!}</a></li>
        @endforeach
    </ol>
</div>
