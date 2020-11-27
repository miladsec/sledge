<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->



    <style>
        *{

        }
    </style>
    <title> @yield('title') </title>
</head>
<body>


@yield('content')


<script src="{{ asset('sledge/library/jquery/jquery.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('sledge/library/bootstrap/bootstrap.min.js') }}" crossorigin="anonymous"></script>



<!-- BEGIN: Vendor JS-->
<script src="/assets/vendors/js/vendors.min.js"></script>
<script src="/assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js"></script>
<script src="/assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
<script src="/assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="/assets/vendors/js/forms/select/select2.full.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="/assets/js/scripts/configs/vertical-menu-dark.js"></script>
<script src="/assets/js/core/app-menu.js"></script>
<script src="/assets/js/core/app.js"></script>
<script src="/assets/js/scripts/components.js"></script>
<script src="/assets/js/scripts/footer.js"></script>
<script src="/assets/js/scripts/customizer.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="/assets/js/scripts/forms/select/form-select2.js"></script>
<!-- END: Page JS-->


<script src="{{ asset('sledge/app.js') }}" crossorigin="anonymous"></script>

</body>
</html>
