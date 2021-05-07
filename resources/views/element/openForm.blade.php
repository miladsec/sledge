<form
    action="{{ route($data->config->formAction ?? '') }}"
    method="{{ $data->config->formMethod ?? '' }}"
    name="{{ $data->name ?? '' }}"
    enctype="{{ $data->enctype ?? '' }}"
    autocomplete="{{ $data->autocomplete ?? '' }}"
    accept-charset="{{ $data->acceptCharset ?? '' }}"
    class="{{ $data->cssClass ?? '' }}"
    id="{{ $data->id ?? '' }}"
    {{ $data->novalidate ?? '' }}
>
@csrf
{{ method_field($data->config->formMethodField ?? '') }}
