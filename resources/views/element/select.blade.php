@php
    $dKey0 = strval(key($data->inputConfig));

    if (!is_array($data->inputConfig[$dKey0]))
        $dKey1 = strval($data->inputConfig[$dKey0]);
    else
        $dKey1 = $data->inputConfig[$dKey0];
@endphp

<div class="form-group">
    <label for="{{ $data->uniqueId ?? '' }}">{{ $data->label ?? '' }}</label>

    <div class="controls">
        <select
            name="{{ $data->name ?? '' }}"
            data-placeholder="{{ $data->placeholder ?? '' }}"
            class="select2 form-control input-select {{ $data->cssClass ?? '' }}"
            id="{{ $data->uniqueId ?? '' }}"
            autocomplete="off"
            {{ $data->validate ?? '' }}
        >
        @foreach($data->value as $value)
            <option value="{{ $value->$dKey0 }}" @if(!empty($data->oldValue)) {{ ($data->oldValue == $value->$dKey0) ? ' selected ' : ''}} @endif >
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
            </option>
        @endforeach
        </select>
    </div>
</div>
