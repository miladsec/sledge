@php
    $dKey0 = strval($data['dKey'][0]);
    $dKey1 = strval($data['dKey'][1]);
@endphp
<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <ul class="list-unstyled mb-0">
            @foreach($data['value'] as $value)
                @php $rand = time().rand(0,100) @endphp
                <li class="d-inline-block mr-2 mb-1">
                    <fieldset>
                        <div class="radio">
                            @if($data['old'] == null)
                                <input
                                    type="radio"
                                    name="{{ $data['name'] }}"
                                    class="{{ $data['class'] }}"
                                    id="{{ $rand }}"
                                @foreach($data['validate'] as $key=>$validate)
                                    {!!   ' '. $key .'="'. $validate .'" ' !!}
                                    @endforeach
                                />
                                <label for="{{ $rand }}">{{ $value->$dKey1 }}</label>
                            @else
                                <input
                                    type="radio"
                                    name="{{ $data['name'] }}"
                                    class="{{ $data['class'] }}"
                                    id="{{ $value->$dKey0 }}"
                                    {{ ( in_array($value->$dKey0, $data['old'])) ? ' checked ' : ''}}
                                @foreach($data['validate'] as $key=>$validate)
                                    {!!   ' '. $key .'="'. $validate .'" ' !!}
                                    @endforeach
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
