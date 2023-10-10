<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<meta http-equiv='cache-control' content='no-cache'>-->
    <!--<meta http-equiv='expires' content='0'>-->
    <!--<meta http-equiv='pragma' content='no-cache'>-->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin_assets/images/favicon.png') }}">
    <title>Real Estate</title>
    <!-- chartist CSS -->
    <link href="{{ asset('admin_assets/node_modules/morrisjs/morris.css') }}" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="{{ asset('admin_assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('admin_assets/css/style.min.css') }}" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="{{ asset('admin_assets/css/pages/dashboard1.css') }}" rel="stylesheet">

    <link href="{{ asset('admin_assets/css/custom.css') }}" rel="stylesheet">

    <link href="{{ asset('admin_assets\css\select2.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('admin_assets\css\fileupload.css') }}" rel="stylesheet" />

    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>

<body class="skin-blue fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Real Estate</p>
        </div>
    </div>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <b>
                            <img src="{{ asset('admin_assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo"
                                style="max-height: 50px" />
                            <img src="{{ asset('admin_assets/images/logo-light-icon.png') }}" alt="homepage"
                                class="light-logo" style="max-height: 50px" />
                        </b>
                        <span>
                            <img src="{{ asset('admin_assets/images/logo-text.png') }}" alt="homepage"
                                class="dark-logo me-2" style="max-height: 50px" />
                            <img src="{{ asset('admin_assets/images/logo-light-text.png') }}" class="light-logo me-2"
                                alt="homepage" style="max-height: 50px""/>
                        </span>
                    </a>
                </div>
                <div class=" navbar-collapse">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item"><a
                                        class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                                        href="javascript:void(0)"><i class="ti-menu"></i></a></li>
                                <li class="nav-item"><a
                                        class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark"
                                        href="javascript:void(0)"><i class="icon-menu"></i></a></li>
                                <li class="nav-item">
                                    <form class="app-search d-none d-md-block d-lg-block">
                                        <input type="text" class="form-control" placeholder="Search & enter">
                                    </form>
                                </li>
                            </ul>
                            <ul class="navbar-nav my-lg-0">
                                <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light"
                                        href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                            </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar">
            @if(Auth::user())
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <div class="p-2 text-center" style="font-size: 22px; font-weight: bold;">{{ Auth::user()->name }}
                    </div>
                    <ul id="sidebarnav">
                        <li><a href="{{ route('profile') }}"><i class="ti-control-record text-success"></i>الملف
                                الشخصي</a></li>
                        @if(Auth::user()->isSuperAdmin())<li><a href="{{ route('user') }}"><i
                                    class="ti-control-record text-success"></i> {{ __('pages.users') }}</a></li>@endif
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->role_id == 2)<li><a
                                href="{{ route('compound') }}"><i class="ti-control-record text-success"></i> {{
                                __('pages.compounds') }}</a></li>@endif
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->role_id == 2)<li><a
                                href="{{ route('building') }}"><i class="ti-control-record text-success"></i> {{
                                __('pages.buildings') }}</a></li>@endif
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->role_id == 2)<li><a
                                href="{{ route('maintenance') }}"><i class="ti-control-record text-success"></i> {{
                                __('pages.maintenances') }}</a></li>@endif
                        {{-- pay rent --}}
                        @if(Auth::user()->role_id == 3)<li><a href="{{ route('home') }}"><i
                                    class="ti-control-record text-success"></i> {{
                                __('pages.pay_rent') }}</a></li>@endif

                        <li><a href="{{ route('financial_transaction') }}"><i
                                    class="ti-control-record text-success"></i> {{__('pages.financial_transactions') }}
                            </a></li>
                        <li><a class="waves-effect waves-dark" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                aria-expanded="false"><i class="ti-control-record text-success"></i><span>{{
                                    __('pages.Logout') }}</span></a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                    </ul>
                </nav>
            </div>
            @endif
        </aside>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span>
                        </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme">1</a>
                                </li>
                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme working">4</a>
                                </li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default-dark"
                                        class="default-dark-theme ">7</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green-dark"
                                        class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a>
                                </li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark"
                                        class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark"
                                        class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark"
                                        class="megna-dark-theme ">12</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('content')

    <script src="{{ asset('admin_assets/node_modules/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('admin_assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('admin_assets/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('admin_assets/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('admin_assets/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('admin_assets/js/custom.min.js') }}"></script>
    <!-- ============================================================== -->
    <script src="{{ asset('admin_assets\js\select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>

    <script src="{{ asset('admin_assets/node_modules/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('admin_assets/node_modules/morrisjs/morris.min.js') }}"></script>
    <script src="{{ asset('admin_assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/dashboard1.js') }}"></script>
    <script src="{{ asset('admin_assets/js/custom1.js') }}"></script>
    <script src="{{ asset('admin_assets/js/script.js') }}"></script>
    <script src="{{ asset('admin_assets/js/lighthouse.js') }}"></script>
    <script src="{{ asset('admin_assets\js\lighthouse.js') }}"></script>
    <script src="{{ asset('admin_assets\js\ajaxActions.js') }}"></script>
    <script src="{{ asset('admin_assets\js\sweetalert2.js') }}"></script>
    <script src="{{ asset('admin_assets\js\bootstrap.main.js') }}"></script>
    <script src="{{ asset('admin_assets\js\dropify.js') }}"></script>
    <script src="{{ asset('admin_assets\js\fileupload.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function(){
            let table = new DataTable('#example', {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                ],
                searching: false,
                responsive: true,
                paging: false,
                info: false,
                language: {
                    "sProcessing": "جارٍ التحميل...",
                    "sLengthMenu": "أظهر _MENU_ مدخلات",
                    "sZeroRecords": "لم يعثر على أية سجلات",
                    "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                    "sInfoPostFix": "",
                    "sSearch": "ابحث:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "الأول",
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                        "sLast": "الأخير"
                    }
                }
            });
            
            $(".sorting").css({ 'text-align': 'right' });
            $("#example_filter").css({ 'margin-bottom': "20px" });
            $(".buttons-excel").css({ 'background': "#0171dc", 'margin-right': "10px !important" });
            $(".dt-buttons").css({ 'padding-top': "15px" });

            function route(){
                return $(this).attr('route');
            }

            function placeholder(){
                return $(this).attr('placeholder');
            }
            
            $(".select2").select2({
                ajax: {
                    url: route,
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (term) {
                        return {
                            term: term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: $.map(response, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        }
                    },
                    cache: true,
                    templateResult: formatRepo,
                    placeholder: placeholder,
                },
            });

            function formatRepo (item) {
                return item.name;
            }
        });

        $('.dropify').dropify();
      
        $('.btn_delete').on('click',function(){
            Swal.fire({
                title: '{{ __("pages.slow_down") }}',
                text: "{{ __('pages.the_deleted_data_cant_be_restored') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("pages.confirm") }}',
                cancelButtonText: '{{ __("pages.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: '{{ __("pages.your_file_has_been_deleted") }}',
                    });

                    $($(this).parent().parent().siblings().eq(0).attr("data-bs-target")).remove();
                    $(this).closest('.record').remove();
                    
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: $(this).attr('route'),
                        method: 'POST'
                    });
                }
            });
        });
        
        $(".sidebartoggler").on('click', function () {
            if ($("body").hasClass("mini-sidebar")) {
                $(".left-sidebar").removeClass("d-none");
            }
            else {
                $(".left-sidebar").addClass("d-none");
            }
        });
        
        $.ajax({
            url: "/verified",
            method: "post"
        });
    </script>

    @yield('js')
</body>

</html>