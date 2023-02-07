<fieldset class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <textarea
            name="{{ $data['name'] }}"
            class="form-control {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }}"
            rows="{{ $data['row'] }}"
            placeholder="{{ $data['placeholder'] }}"
            autocomplete="off"
            @foreach($data['validate'] as $key=>$validate)
                {!!   ' '. $key .'="'. $validate .'" ' !!}
            @endforeach
        >{{ $data['value'] ?? old($data['name']) }}</textarea>
    </div>
</fieldset>
