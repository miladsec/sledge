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
            name="{{ $data['name'] }}"
            data-placeholder="{{ $data['placeholder'] }}"
            class="select2 form-control input-select {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }} select2-icons"
            autocomplete="off"
        @foreach($data['validate'] as $key=>$validate)
            {!!   ' '. $key .'="'. $validate .'" ' !!}
            @endforeach
        >
            @if($data['old'] == null)

                <option value="" selected >{{ $data['placeholder'] }}</option>
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}">
                        @php
                            if (is_array($dKey1)){
                                $res = '';
                                foreach ($dKey1 as $v){
                                    $res .= $value->$v . '-';
                                }
                            }else{
                                $res = $dKey1;
                            }
                            echo $res;
                        @endphp
                    </option>
                @endforeach

            @else

                <option value="" selected >{{ $data['placeholder'] }}</option>
                @foreach($data['value'] as $value)
                    <option value="{{ $value->$dKey0 }}"
                        {{ ($data['old'] == $value->$dKey0) ? ' selected ' : ''}}>
                        @php
                            if (is_array($dKey1)){
                                $res = '';
                                foreach ($dKey1 as $v){
                                    $res .= $value->$v . '-';
                                }
                            }else{
                                $res = $dKey1;
                            }
                            echo $res;
                        @endphp
                    </option>
                @endforeach

            @endif

        </select>
    </div>
</div>
