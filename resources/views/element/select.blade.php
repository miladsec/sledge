<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>

    <div class="controls">
        <select name="{{ $data['name'] }}" data-placeholder="{{ $data['placeholder'] }}"
                class="select2-icons form-control input-select {{ $data['class'] }}" id="{{ $data['uniqueId'] }} select2-icons"
                autocomplete="off">
            @if($data['action'] == null)
                <option value="" selected disabled>{{ $data['placeholder'] }}</option>

                @foreach($data['value'] as $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            @else
                <option value="-1" selected disabled>{{ $data['placeholder'] }}</option>

                @foreach($data['value'] as $value)
                    <option
                        value="{{ $value->id }}" {{ ($data['action'] == $value->id) ? ' selected ' : ''}}>{{ $value->name }}</option>
                @endforeach

            @endif

        </select>
    </div>
</div>
