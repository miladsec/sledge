@php
    $dKey0 = strval(key($data->inputConfig));

    if (!is_array($data->inputConfig[$dKey0]))
        $dKey1 = strval($data->inputConfig[$dKey0]);
    else
        $dKey1 = $data->inputConfig[$dKey0];
@endphp
<div class="form-group">
    <label for="{{ $data->uniqueId }}">{{ $data->label }}</label>
    <div class="controls">
        <ul class="list-unstyled mb-0">
            @foreach($data->value as $value)
            <li class="d-inline-block mr-2 mb-1">
                <fieldset>
                    <div class="radio">
                        <input
                            type="radio"
                            name="{{ $data->name }}"
                            class="{{ $data->cssClass }}"
                            id="{{ $data->uniqueId . $value->$dKey0 }}"
                            value="{{ $value->$dKey0 }}"
                            @if(!empty($data->oldValue)) {{ ($data->oldValue == $value->$dKey0) ? ' checked ' : ''}} @endif
                            {{ $data->validate ?? '' }}
                        />
                        <label for="{{ $data->uniqueId . $value->$dKey0 }}">
                            @php
                                $secData = clone $value;
                                if (is_array($dKey1)){
                                    $res = '';
                                    foreach ($dKey1 as $v){
                                        $str = explode('.', $v);
                                        if (count($str)>1){
                                            for ($i = 0; $i < count($str); $i++) {
                                                $value = $value->{$str[$i]};
                                            }
                                            $res .= $value . '-';

                                            $value = $secData;
                                        }else{
                                            $res .= $value->$v . '-';
                                        }
                                    }
                                    echo $res;
                                }else{
                                    echo $value->$dKey1;
                                }
                            @endphp
                        </label>
                    </div>
                </fieldset>
            </li>
            @endforeach
        </ul>
    </div>
</div>
