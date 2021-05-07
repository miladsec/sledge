<button type="submit"
        name="{{ $data->name ?? '' }}"
        class="btn btn-primary {{ $data->cssClass ?? '' }}"
        id="{{ $data->id ?? '' }}">
        {{ $data->value ?? '' }}
</button>
