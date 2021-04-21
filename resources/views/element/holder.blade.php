<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <div class="{{ $data['selector'] }}">
            {!! $data['value'] !!}
            تا
            <span class="obs2">

            </span>
        </div>
        <input type="hidden" name="{{ $data['bind']['name'] }}" class="{{ $data['bind']['class'] }}">
    </div>
</div>
