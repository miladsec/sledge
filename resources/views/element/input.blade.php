<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>

    <div class="controls">
        <input
            type="{{ $data['type'] }}"
            name="{{ $data['name'] }}"
            value="{{ $data['value'] }}"
            placeholder="{{ $data['placeholder'] }}"
            class="form-control input-label {{ $data['class'] }}"
            id="{{ $data['uniqueId'] }}"
            autocomplete="off"
            @if(!empty($data['validate']) && strpos('required', $data['validate']) !== false)
            data-validation-required-message="{{ config('sledge.validation.required') }}" @endif
            {{ $data['validate'] }}
        />
    </div>
</div>
