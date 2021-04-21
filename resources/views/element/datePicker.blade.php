<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <input
            type="text"
            name="{{ $data['name'] }}"
            value="{{ $data['value'] ?? old($data['name']) }}"
            placeholder="{{ $data['placeholder'] }}"
            class="form-control input-label {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }}"
            autocomplete="off"
            readonly
            @foreach($data['validate'] as $key=>$validate)
                {!!   ' '. $key .'="'. $validate .'" ' !!}
            @endforeach
        />
        <input type="hidden" name="{{ $data['bind']['name'] }}" class="{{ $data['bind']['class'] }}">
    </div>
</div>
