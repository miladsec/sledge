@extends('layouts.app')

@section('content')
    <!-- Zero configuration table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title ?? '' }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            @if(Session::has('text'))
                                <div class="alert alert-{{ Session::get('status') }} alert-dismissible mb-2"
                                     role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="بستن">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-like"></i>
                                        <span>
                                          {{ Session::get('text') }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <a href="" class="btn btn-primary mr-1 mb-1"><i class="bx bx-plus"></i><span
                                    class="align-middle ml-25">افزودن</span></a>

                            <div class="table-responsive">
                                <table class="table zero-configuration">
                                    <thead>
                                    <tr>
                                        @foreach($cc as $c)
                                            <th>{{ $c['text'] }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--@foreach($cc[0] as $key => $data)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            @foreach($data as $k=>$d)
                                                <td>{!! $d !!} </td>
                                            @endforeach
                                        </tr>
                                    @endforeach--}}
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Zero configuration table -->

@stop

@section('my_js')
    <script>
        $(document).ready(function() {

            $('#tablename').DataTable( {
                Processing: true,
                serverSide: true,
                ajax: '{{ route('category.index') }}'
            } );
        } );

    </script>
@stop
