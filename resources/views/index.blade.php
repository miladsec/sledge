@extends('sledge::layouts.app')

@section('breadcrumb')
    @include('sledge::layouts.sections.breadcrumb')
@endsection

@section('content')
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title ?? '' }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            @if(!empty($sledge['button']))
                                @foreach($sledge['button'] as $btn)
                                    <a href="{{ $btn['url'] }}" class="btn btn-primary mr-1 mb-1">
                                        <i class="{{ $btn['icon'] }}"></i>
                                        <span class="align-middle ml-25">{{ $btn['text'] }}</span>
                                    </a>
                                @endforeach
                            @endif
                            <div class="table-responsive">
                                <table id="dataTable" class="table">
                                    <thead>
                                    <tr>
                                        @foreach($sledge['table'] as $column)
                                            @if($column == 'action')
                                                <th>{{ config('sledge.index.actionColumnName') }}</th>
                                            @else
                                                <th>{{ $column->title }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
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

    <form id="deleteForm" style="display: none" action="" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>
@endsection

@section('js')
    <script>
        $(document).ready(function () {

            $('#dataTable').DataTable({
                Processing: true,
                serverSide: true,
                "ajax": {
                    "type": "GET",
                    "url": '{{url()->current()}}'+window.location.search,
                    "dataSrc": function (json) {
                        return json.data;
                    },beforeSend: function() {
                        $($('.table-responsive')).block({
                            message: '<div class="bx bx-reset icon-spin font-medium-2" style="padding-bottom:1.75px"></div>',
                            overlayCSS: {
                                backgroundColor: 'white',
                                opacity: 0,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },complete: function() {
                        $($('.table-responsive')).unblock();
                    }
                }
            });

            @if(Session::has('type'))
                Swal.fire({
                    position: 'top-center',
                    type: '{{ Session::get('type') }}',
                    title: '{{ Session::get('title') }}',
                    showConfirmButton: false,
                    timer: 2500,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            @endif

            $(document).on('click', 'a.confirm', function(e) {
                e.preventDefault();
                let data = this;
                Swal.fire({
                    title: 'آیا مطمئنید؟',
                    text: "این عمل قابل بازگشت نخواهد بود!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'تایید',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    cancelButtonText: 'انصراف',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        let url = $(data).attr('href')

                        if ($(data).hasClass('delete')){
                            $("#deleteForm").attr('action', url).submit();
                        }else {
                            window.location.href = url;
                        }

                    }
                });
            });
        });

    </script>
    @if(!empty($sledge['button']))
        {!! $sledge['script'] !!}
    @endif
@stop
