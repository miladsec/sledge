@php
    $dKey0 = strval($data['dKey'][0]);
    $dKey1 = strval($data['dKey'][1]);
@endphp
<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>

    <div class="controls">
        <select
            name="{{ $data['name'] }}"
            data-placeholder="{{ $data['placeholder'] }}"
            class="select2-icons form-control input-select {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }} select2-icons"
            autocomplete="off"
            @if(strpos('required', $data['validate']) !== false)
            data-validation-required-message="{{ config('sledge.validation.required') }}" @endif
            {{ $data['validate'] }}
        >
            @if($data['old'] == null)

                <option value="" selected >{{ $data['placeholder'] }}</option>
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}">{{ $value->$dKey1 }}</option>
                @endforeach

            @else

                <option value="" selected >{{ $data['placeholder'] }}</option>
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}"
                        {{ ($data['old'] == $value->$dKey0) ? ' selected ' : ''}}>
                        {{ $value->$dKey1 }}
                    </option>
                @endforeach

            @endif

        </select>
    </div>
</div>
