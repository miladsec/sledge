@extends('layouts.panel.app')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            {!!   $cc['navLink'] !!}
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ $title ?? '' }}</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        @if(!empty($cc['metaData']))
                                            <a href="{{ $cc['metaData']['url'] }}" class="btn btn-primary mr-1 mb-1">
                                                <i class="{{ $cc['metaData']['icon'] }}"></i>
                                                <span class="align-middle ml-25">{{ $cc['metaData']['text'] }}</span>
                                            </a>
                                        @endif
                                        <button class="DeleteClass" id="footer-alert" href="#">TST</button>

                                        <div class="table-responsive">
                                            <table id="tablename" class="table">
                                                <thead>
                                                <tr>
                                                    @foreach($cc['table'] as $c)
                                                        @if(isset($c['columnAction']))
                                                            <th>{{ 'عملیات' }}</th>
                                                        @else
                                                            <th>{{ $c['text'] }}</th>
                                                        @endif
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
                <!-- Dashboard Ecommerce ends -->
            </div>
        </div>
    </div>

    <form id="deleteForm" style="display: none" action="" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>
@endsection

@section('my_js')
    <script>
        $(document).ready(function () {

            $('#tablename').DataTable({
                Processing: true,
                serverSide: true,
                "ajax": {
                    "type": "GET",
                    "url": "{{url()->current()}}"+window.location.search,
                    "dataSrc": function (json) {
                        return json.data;
                    },beforeSend: function() {
                        let block_ele = $('.table-responsive');
                        $(block_ele).block({
                            message: '<div class="spinner-grow text-primary" role="status"></div>',
                            overlayCSS: {
                                backgroundColor: 'white',
                                opacity: 0.3,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });

                    },complete: function() {
                        let block_ele = $('.table-responsive');
                        $(block_ele).unblock();
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

                        if ($(data).hasClass('dl')){
                            $("#deleteForm").attr('action', url).submit();
                        }else {
                            window.location.href = url;
                        }

                    }
                });
            });

            $('{!! $cc['confirm']['selector'] !!}').on('click', function () {
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
                            {!!   $cc['confirm']['script'] !!}
                        }
                    });
                });

            /*"ajax": {
                "type": "GET",
                    "url": "ss",
                    "dataSrc": function (json) {
                    return json.data;
                }
            }*/

            /*var table = $('#tablename').DataTable( {
                Processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    { data: 0 },
                    { data: 1 },
                    { data: 2 },
                    { data: 3 },
                    { data: 4 }
                ]
            } );*/
            // console.log(table.page.info())
            // $('#tablename').on( 'page.dt', function () {
            //     var info = table.page.info();
            //     console.log(table.page.info())
            //     alert('Showing page: '+info.page+' of '+info.pages)
            //     // $('#pageInfo').html( 'Showing page: '+info.page+' of '+info.pages );
            // } );
        });

    </script>



    {!! (isset($customScript)) ? $customScript : '' !!}
@stop
