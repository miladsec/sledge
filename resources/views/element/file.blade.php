<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls custom-file">
        <input
            type="{{ $data['type'] }}"
            name="{{ $data['name'] }}"
            value="{{ $data['value'] ?? old($data['name']) }}"
            class="custom-file-input {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }}"
            autocomplete="off"
        @foreach($data['validate'] as $key=>$validate)
            {!!   ' '. $key .'="'. $validate .'" ' !!}
            @endforeach
        />
        <label class="custom-file-label" for="{{ $data['uniqueId'] }}">{{ $data['placeholder'] }}</label>
    </div>
</div>
