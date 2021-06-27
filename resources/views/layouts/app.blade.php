<!DOCTYPE html>
<html class="loading" lang="fa" data-textdirection="rtl" dir="rtl">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>توری محتوا</title>
    <link rel="shortcut icon" type="image/x-icon" href="/src/assets/images/ico/favicon.ico">
    <meta name="theme-color" content="#5A8DEE">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="/src/assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/vendors/css/ui/prism.min.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/vendors/css/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/vendors/css/extensions/sweetalert2.min.css">


    <link rel="stylesheet" type="text/css" href="/src/assets/vendors/css/tables/datatable/datatables.min.css">


    <link rel="stylesheet" type="text/css" href="/src/assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/src/assets/css/core/menu/menu-types/vertical-menu.css">

    <link href="/src/assets/css/plugins/date-picker/persian-datepicker.min.css" rel="stylesheet" type="text/css">
    <!-- END: Page CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">

<!-- BEGIN: Header-->
@include('sledge::layouts.sections._header')
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
@include('sledge::layouts.sections._sidebar')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            @yield('breadcrumb')
        </div>
        <div class="content-body"><!--Grid options-->
            @yield('content')
        </div>
    </div>
</div>
<!-- END: Content-->


<!-- BEGIN: Customizer-->
<div class="customizer d-none d-md-block"><a class="customizer-close" href="#"><i class="bx bx-x"></i></a><a
        class="customizer-toggle" href="#"><i class="bx bx-cog bx bx-spin white"></i></a>
    <div class="customizer-content p-2">
        <h4 class="text-uppercase mb-0 mt-n50">سفارشی سازی قالب</h4>
        <small>سفارشی سازی کنید و به صورت زنده مشاهده کنید.</small>
        <hr>
        <!-- Theme options starts -->
        <h5 class="mt-n25">طرح قالب</h5>
        <div class="theme-layouts">
            <div class="d-flex justify-content-start">
                <div class="mx-50">
                    <fieldset>
                        <div class="radio">
                            <input type="radio" name="layoutOptions" value="false" id="radio-light" class="layout-name"
                                   data-layout="" checked>
                            <label for="radio-light">روشن</label>
                        </div>
                    </fieldset>
                </div>
                <div class="mx-50">
                    <fieldset>
                        <div class="radio">
                            <input type="radio" name="layoutOptions" value="false" id="radio-dark" class="layout-name"
                                   data-layout="dark-layout">
                            <label for="radio-dark">تیره</label>
                        </div>
                    </fieldset>
                </div>
                <div class="mx-50">
                    <fieldset>
                        <div class="radio">
                            <input type="radio" name="layoutOptions" value="false" id="radio-semi-dark"
                                   class="layout-name" data-layout="semi-dark-layout">
                            <label for="radio-semi-dark">نیمه تیره</label>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <!-- Theme options starts -->
        <hr>

        <!-- Menu Colors Starts -->
        <div id="customizer-theme-colors">
            <h5>رنگ های فهرست</h5>
            <ul class="list-inline unstyled-list">
                <li class="color-box bg-primary selected" data-color="theme-primary"></li>
                <li class="color-box bg-success" data-color="theme-success"></li>
                <li class="color-box bg-danger" data-color="theme-danger"></li>
                <li class="color-box bg-info" data-color="theme-info"></li>
                <li class="color-box bg-warning" data-color="theme-warning"></li>
                <li class="color-box bg-dark" data-color="theme-dark"></li>
            </ul>
            <hr>
        </div>
        <!-- Menu Colors Ends -->
        <!-- Menu Icon Animation Starts -->
        <div id="menu-icon-animation">
            <div class="d-flex justify-content-between align-items-center">
                <div class="icon-animation-title">
                    <h5 class="pt-25">انیمیشن آیکن ها</h5>
                </div>
                <div class="icon-animation-switch">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" checked id="icon-animation-switch">
                        <label class="custom-control-label" for="icon-animation-switch"></label>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <!-- Menu Icon Animation Ends -->
        <!-- Collapse sidebar switch starts -->
        <div class="collapse-sidebar d-flex justify-content-between align-items-center">
            <div class="collapse-option-title">
                <h5 class="pt-25">جمع کردن فهرست</h5>
            </div>
            <div class="collapse-option-switch">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="collapse-sidebar-switch">
                    <label class="custom-control-label" for="collapse-sidebar-switch"></label>
                </div>
            </div>
        </div>
        <!-- Collapse sidebar switch Ends -->
        <hr>

        <!-- Navbar colors starts -->
        <div id="customizer-navbar-colors">
            <h5>رنگ های نوار بالایی</h5>
            <ul class="list-inline unstyled-list">
                <li class="color-box bg-white border selected" data-navbar-default=""></li>
                <li class="color-box bg-primary" data-navbar-color="bg-primary"></li>
                <li class="color-box bg-success" data-navbar-color="bg-success"></li>
                <li class="color-box bg-danger" data-navbar-color="bg-danger"></li>
                <li class="color-box bg-info" data-navbar-color="bg-info"></li>
                <li class="color-box bg-warning" data-navbar-color="bg-warning"></li>
                <li class="color-box bg-dark" data-navbar-color="bg-dark"></li>
            </ul>
            <small><strong>نکته :</strong> این گزینه تنها در حالت نوار ثابت و در هنگام اسکرول صفحه نمایش داده خواهد شد.</small>
            <hr>
        </div>
        <!-- Navbar colors starts -->
        <!-- Navbar Type Starts -->
        <h5 class="mt-n25">نوع نوار بالایی</h5>
        <div class="navbar-type d-flex justify-content-start">
            <div class="hidden-ele mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="navbarType" value="false" id="navbar-hidden">
                        <label for="navbar-hidden">مخفی</label>
                    </div>
                </fieldset>
            </div>
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="navbarType" value="false" id="navbar-static">
                        <label for="navbar-static">ایستا</label>
                    </div>
                </fieldset>
            </div>
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="navbarType" value="false" id="navbar-sticky" checked>
                        <label for="navbar-sticky">ثابت</label>
                    </div>
                </fieldset>
            </div>
        </div>
        <hr>
        <!-- Navbar Type Starts -->

        <!-- Footer Type Starts -->
        <h5 class="mt-n25">نوع فوتر</h5>
        <div class="footer-type d-flex justify-content-start">
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="footerType" value="false" id="footer-hidden">
                        <label for="footer-hidden">مخفی</label>
                    </div>
                </fieldset>
            </div>
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="footerType" value="false" id="footer-static" checked>
                        <label for="footer-static">ایستا</label>
                    </div>
                </fieldset>
            </div>
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="footerType" value="false" id="footer-sticky">
                        <label for="footer-sticky" class="">چسبان</label>
                    </div>
                </fieldset>
            </div>
        </div>
        <!-- Footer Type Ends -->
        <hr>

        <!-- Card Shadow Starts-->
        <div class="card-shadow d-flex justify-content-between align-items-center py-25">
            <div class="hide-scroll-title">
                <h5 class="pt-25">سایه کارت</h5>
            </div>
            <div class="card-shadow-switch">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" checked id="card-shadow-switch">
                    <label class="custom-control-label" for="card-shadow-switch"></label>
                </div>
            </div>
        </div>
        <!-- Card Shadow Ends-->
        <hr>

        <!-- Hide Scroll To Top Starts-->
        <div class="hide-scroll-to-top d-flex justify-content-between align-items-center py-25">
            <div class="hide-scroll-title">
                <h5 class="pt-25">مخفی سازی دکمه اسکرول به بالا</h5>
            </div>
            <div class="hide-scroll-top-switch">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="hide-scroll-top-switch">
                    <label class="custom-control-label" for="hide-scroll-top-switch"></label>
                </div>
            </div>
        </div>
        <!-- Hide Scroll To Top Ends-->
    </div>
</div>
<!-- End: Customizer-->

</div>

<!-- BEGIN: Footer-->
@include('sledge::layouts.sections._footer')
<!-- END: Footer-->


<!-- BEGIN: Vendor JS-->
<script src="/src/assets/vendors/js/vendors.min.js"></script>
<script src="/src/assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js"></script>
<script src="/src/assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
<script src="/src/assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="/src/assets/vendors/js/ui/prism.min.js"></script>

<script src="/src/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
<script src="/src/assets/vendors/js/tables/datatable/vfs_fonts.js"></script>

<script src="/src/assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

<script src="/src/assets/vendors/js/forms/select/select2.full.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="/src/assets/js/scripts/configs/vertical-menu-dark.js"></script>
<script src="/src/assets/js/core/app-menu.js"></script>
<script src="/src/assets/js/core/app.js"></script>
<script src="/src/assets/js/scripts/components.js"></script>
<script src="/src/assets/js/scripts/footer.js"></script>
<script src="/src/assets/js/scripts/customizer.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="/src/assets/js/scripts/datatables/datatable.js"></script>
<script src="/src/assets/js/scripts/extensions/sweet-alerts.js"></script>

<script src="/src/assets/js/scripts/forms/select/form-select2.js"></script>

<script src="/src/assets/js/scripts/date-picker/persian-date.min.js"></script>
<script src="/src/assets/js/scripts/date-picker/persian-datepicker.min.js"></script>
<!-- END: Page JS-->
<script>
    $('.date-picker').pDatepicker({
        format: "LLLL",
        persianDigit: false,
        initialValue: true,
        initialValueType: 'persian',
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
            enabled: false,
            meridiem: {
                enabled: true
            }
        }
    });
</script>
@yield('js')
</body>
<!-- END: Body-->
</html>
