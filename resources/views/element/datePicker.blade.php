<div class="form-group">
    <label for="{{ $data->uniqueId }}">{{ $data->label }}</label>
    <div class="controls">
        <input
                type="text"
                value="{{ $data->value ?? $data->oldValue ?? old($data->name) }}"
                placeholder="{{ $data->placeholder }}"
                class="form-control form-control-solid {{ $data->cssClass }}"
                id="{{ $data->uniqueId }}"
                autocomplete="off"
                readonly
                {{ $data->validate }}
        />
        <input type="hidden" name="{{ $data->name }}" class="place-{{ $data->uniqueId }}">
    </div>
</div>

<script>
    $('#{{$data->uniqueId}}').pDatepicker({
        format: "{{ $data->timePicker ? 'H:M YYYY/MM/DD' : 'YYYY/MM/DD' }}",
        observer: true,
        persianDigit: false,
        initialValue: true,
        initialValueType: 'persian',
        altField: '.place-{{ $data->uniqueId }}',
        toolbox: {
            enabled: true,
            todayButton: {
                enabled: true,
                text: {
                    fa: 'امروز',
                },
            },
            submitButton: {
                enabled: true,
                text: {
                    fa: 'تایید',
                },
            },
            calendarSwitch: {
                enabled: false,
            }
        },
        calendar: {
            persian: {
                'locale': 'fa',
                'showHint': false,
                'leapYearMode': 'algorithmic' // "astronomical"
            },
        },
        timePicker: {
            enabled: {{ $data->timePicker ? 'true' : 'false' }},
            meridiem: {
                enabled: true
            }
        },
        responsive: true
    });
</script>
