<!DOCTYPE html>
<html class="loading" lang="fa" data-textdirection="rtl" dir="rtl">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Sledge</title>

    <!-- BEGIN: Theme CSS-->

    <!-- END: Theme CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body>

<!-- BEGIN: Header-->
@include('sledge::layouts.sections._header')
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
@include('sledge::layouts.sections._sidebar')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="content-header row">
    @yield('breadcrumb')
</div>
<div class="content-body"><!--Grid options-->
    @yield('content')
</div>
<!-- END: Content-->

</div>

<!-- BEGIN: Footer-->
@include('sledge::layouts.sections._footer')
<!-- END: Footer-->


<!-- BEGIN: Theme JS-->

<!-- END: Theme JS-->

@yield('js')
</body>
<!-- END: Body-->
</html>
