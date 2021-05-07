@php
    $dKey0 = strval(key($data->selectConfig));

    if (!is_array($data->selectConfig[$dKey0]))
        $dKey1 = strval($data->selectConfig[$dKey0]);
    else
        $dKey1 = $data->selectConfig[$dKey0];
@endphp
<div class="form-group">
    <label for="{{ $data->uniqueId ?? '' }}">{{ $data->label ?? '' }}</label>

    <div class="controls">
        <select
            name="{{ $data->name ?? '' }}[]"
            data-placeholder="{{ $data->placeholder ?? '' }}"
            class="select2 form-control input-multi-select {{ $data->cssClass ?? '' }}"
            id="{{ $data->uniqueId ?? '' }}"
            autocomplete="{{ $data->autocomplete ?? '' }}"
            multiple
            {{ $data->validate ?? '' }}
        >
            @foreach($data->value as $value)
                <option value="{{ $value->$dKey0 }}" @if(!empty($data->oldValue)) {{ ( in_array($value->$dKey0, $data->oldValue)) ? ' selected ' : ''}} @endif>
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
                                    $res .= $value->$v . ' ';
                                }
                            }
                            echo $res;
                        }else{
                            echo $value->$dKey1;
                        }
                    @endphp
                </option>
            @endforeach
        </select>
    </div>
</div>
