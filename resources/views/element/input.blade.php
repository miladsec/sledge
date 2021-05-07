<div class="form-group">
    <label for="{{ $data->uniqueId ?? '' }}">{{ $data->label ?? '' }}</label>
    <div class="controls">
        <input
            type="{{ $data->type ?? '' }}"
            name="{{ $data->name ?? '' }}"
            value="{{ $data->value ?? old($data->name ?? '') }}"
            placeholder="{{ $data->placeholder ?? '' }}"
            class="form-control input-label {{ $data->cssClass ?? '' }}"
            id="{{ $data->uniqueId ?? '' }}"
            autocomplete="{{ $data->autocomplete ?? '' }}"
            {{ $data->validate ?? '' }}
        />
    </div>
</div>
