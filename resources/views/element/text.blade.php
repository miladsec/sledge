<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>

    <div class="controls">
        <input type="text" value="{{ $data['value'] }}" name="{{ $data['name'] }}"
               class="form-control input-label {{ $data['class'] }}" id="{{ $data['uniqueId'] }}"
               placeholder="{{ $data['placeholder'] }}" autocomplete="off">
    </div>
</div>
