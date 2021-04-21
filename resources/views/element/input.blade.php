<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <input
            type="{{ $data['type'] }}"
            name="{{ $data['name'] }}"
            value="{{ $data['value'] ?? old($data['name']) }}"
            placeholder="{{ $data['placeholder'] }}"
            class="form-control input-label {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }}"
            autocomplete="off"
            @foreach($data['validate'] as $key=>$validate)
                {!!   ' '. $key .'="'. $validate .'" ' !!}
            @endforeach
        />
    </div>
</div>
