<div class="form-group">
    <label for="{{ $data['uniqueId'] }}">{{ $data['label'] }}</label>
    <div class="controls">
        <ul class="list-unstyled mb-0">
            @foreach($data['value'] as $value)
                <li class="d-inline-block mr-2 mb-1">
                    <fieldset>
                        <div class="checkbox checkbox-primary">
                            @if($data['action'] == null)
                                <input type="checkbox" name="{{ $data['name'] }}[]" id="{{ $value->id }}">
                                <label for="{{ $value->id }}">{{ $value->name }}</label>
                            @else
                                <input type="checkbox" name="{{ $data['name'] }}[]" id="{{ $value->id }}" {{ ( in_array($value->id, $data['action'])) ? ' checked ' : ''}}>
                                <label for="{{ $value->id }}">{{ $value->name }}</label>
                            @endif
                        </div>
                    </fieldset>
                </li>
            @endforeach
        </ul>
    </div>
</div>
