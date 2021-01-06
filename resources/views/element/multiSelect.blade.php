@php
    $dKey0 = strval($data['dKey'][0]);

    if (!is_array($data['dKey'][1]))
        $dKey1 = strval($data['dKey'][1]);
    else
        $dKey1 = $data['dKey'][1];

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
        @foreach($data['validate'] as $key=>$validate)
            {!!   ' '. $key .'="'. $validate .'" ' !!}
            @endforeach
        >

            @if($data['dKey'] == null)
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}">
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
            @else
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}" {{ ( in_array($value->$dKey0, $data['old'])) ? ' selected ' : ''}}>
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
            @endif
        </select>
    </div>
</div>
