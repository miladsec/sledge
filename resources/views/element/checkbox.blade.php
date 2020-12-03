@php
    $dKey0 = strval($data['dKey'][0]);
    $dKey1 = strval($data['dKey'][1]);
@endphp
<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <ul class="list-unstyled mb-0">
            @foreach($data['value'] as $value)
                <li class="d-inline-block mr-2 mb-1">
                    <fieldset>
                        <div class="checkbox checkbox-primary">
                            @if($data['old'] == null)
                                <input
                                    type="checkbox"
                                    name="{{ $data['name'] }}[]"
                                    class="{{ $data['class'] }}"
                                    id="{{ $value->$dKey0 }}"
                                />
                                <label for="{{ $value->$dKey0 }}">{{ $value->$dKey1 }}</label>
                            @else
                                <input
                                    type="checkbox"
                                    name="{{ $data['name'] }}[]"
                                    class="{{ $data['class'] }}"
                                    id="{{ $value->$dKey0 }}" {{ ( in_array($value->$dKey0, $data['old'])) ? ' checked ' : ''}}
                                />
                                <label for="{{ $value->$dKey0 }}">{{ $value->$dKey1 }}</label>
                            @endif
                        </div>
                    </fieldset>
                </li>
            @endforeach
        </ul>
    </div>
</div>
