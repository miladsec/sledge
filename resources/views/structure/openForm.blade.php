<form action="{{ $data['action'] }}" method="{{ $data['method'] }}" name="{{ $data['name'] }}"
      enctype="{{ $data['enctype'] }}" {{ $data['novalidate'] }}autocomplete="{{ $data['autocomplete'] }}"
      accept-charset="{{ $data['accept-charset'] }}" class="{{ $data['class'] }}" id="{{ $data['id'] }}">
@csrf
