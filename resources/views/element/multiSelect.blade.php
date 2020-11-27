<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>

    <div class="controls">
        <select name="{{ $data['name'] }}[]" data-placeholder="{{ $data['placeholder'] }}"
                class="select2-icons form-control input-multi-select {{ $data['class'] }}" id="{{ $data['uniqueId'] }} multiple-select2-icons" multiple
                autocomplete="off">

            @if($data['action'] == null)
                @foreach($data['value'] as $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            @else
                @foreach($data['value'] as $value)
                    <option value="{{ $value->id }}" {{ ( in_array($value->id, $data['action'])) ? ' selected ' : ''}}>
                        {{ $value->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
