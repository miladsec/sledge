@php
    $dKey0 = strval($data['dKey'][0]);
    $dKey1 = strval($data['dKey'][1]);
@endphp
<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>

    <div class="controls">
        <select
            name="{{ $data['name'] }}[]"
            data-placeholder="{{ $data['placeholder'] }}"
            class="select2-icons form-control input-multi-select {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }} multiple-select2-icons"
            autocomplete="off"
            multiple
            @if(strpos('required', $data['validate']) !== false)
            data-validation-required-message="{{ config('sledge.validation.required') }}" @endif
            {{ $data['validate'] }}
        >

            @if($data['dKey'] == null)
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}">{{ $value->$dKey1 }}</option>
                @endforeach
            @else
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}" {{ ( in_array($value->$dKey0, $data['old'])) ? ' selected ' : ''}}>
                        {{ $value->$dKey1 }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
