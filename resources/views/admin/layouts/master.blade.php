<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords"
        content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />
    @include('admin.layouts.head')
</head>

<body class="main-body app sidebar-mini">
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    @include('admin.layouts.main-sidebar')
    <!-- main-content -->
    <div class="main-content app-content">
        @include('admin.layouts.main-header')
        <!-- container -->
        <div class="container-fluid">
            @yield('page-header')
            @yield('content')
            @include('admin.layouts.sidebar-right')
            @include('admin.layouts.models')
            @include('admin.layouts.footer')
            @include('admin.layouts.footer-scripts')


            @if (session('alert-type') && session('title') && session('message'))
                <script>
                    window.onload = function() {
                        swal({
                            type: '{{ session('alert-type') }}', // success, warning, info, error
                            title: '{{ session('title') }}',
                            text: '{{ session('message') }}',
                            showConfirmButton: true,
                        });
                    }
                </script>
            @endif

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // استهداف جميع أزرار الحذف
                    document.querySelectorAll(".delete-btn").forEach((button) => {
                        button.addEventListener("click", function() {
                            const Id = this.dataset.id; // الحصول على ID العنصر
                            const form = document.getElementById(`delete-form-${Id}`); // استهداف النموذج

                            swal({
                                    title: "{{ trans('dashboard.are_you_sure') }}",
                                    text: "{{ trans('dashboard.you_will_not_be_able_to_revert_this') }}",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "{{ trans('dashboard.yes') }}",
                                    cancelButtonText: "{{ trans('dashboard.no') }}",
                                },
                                function(isConfirm) {
                                    if (isConfirm) {
                                        // اذا تم تاكيد الحذف
                                        form.submit(); // ارسال النموذج
                                    }
                                }
                            );
                        });
                    });
                });
            </script>
</body>

</html>
