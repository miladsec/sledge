<fieldset class="form-group">
    <label for="{{ $data->uniqueId ?? '' }}">{{ $data->label ?? '' }}</label>
    <div class="controls">
        <textarea
            name="{{ $data->name ?? '' }}"
            class="form-control {{ $data->cssClass ?? '' }}"
            id="{{ $data->uniqueId ?? '' }}"
            rows="{{ $data->row ?? '' }}"
            placeholder="{{ $data->placeholder ?? '' }}"
            autocomplete="{{ $data->autocomplete ?? '' }}"
            {{ $data->validate ?? '' }}
        >{{ $data->value ?? old($data->name ?? '') }}</textarea>
    </div>
</fieldset>
