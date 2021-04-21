<form
    action="{{ $data['action'] }}"
    method="{{ $data['method'][0] }}"
    name="{{ $data['name'] }}"
    enctype="{{ $data['enctype'] }}"
    autocomplete="{{ $data['autocomplete'] }}"
    accept-charset="{{ $data['accept-charset'] }}"
    class="{{ $data['class'] }}"
    id="{{ $data['id'] }}"
    {{ $data['novalidate'] }}
>
@csrf
{{ $data['method'][1] }}
