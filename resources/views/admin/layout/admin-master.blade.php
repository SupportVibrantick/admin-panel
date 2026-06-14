<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>@yield("title")</title>

    <!-- Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="DexignZone" />
    <meta name="robots" content="" />

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @php
        $settings = App\Models\Setting::first();
    @endphp

    <!-- Favicon icon -->
    {{-- <link rel="shortcut icon" href="{{ url('admin/images/logo/d.png') }}" type="image/x-icon"> --}}
    <link rel="icon" type="image/png"
      href="{{ $settings && $settings->favicon
            ? Storage::url($settings->favicon)
            : url('images/favicon.png') }}">

    <link href="{{ url('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <link href="{{ url('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">


    {{-- summernote --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">



    <!-- Style Css -->
    <link href="{{ url('css/style.css') }}" rel="stylesheet" />
    {{-- data tables --}}
    <!-- Datatable -->
    <link href="{{ url('vendor/datatables/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- CSS (optional but recommended for styling) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.min.js"></script>

    {{-- for the push style of the pages --}}
    @stack('style')
    <style>
        .nav-tabs .nav-link.active {
            background: #2bc155;
            color: white;
        }

        .nav-tabs .nav-link {
            background: #dde2de;
            color: black;
        }

        .nav-item {
            margin-right: 5px;
        }

        .DZ-theme-btn.DZ-bt-buy-now {
            display: none;
        }

        .DZ-theme-btn.DZ-bt-support-now {
            display: none;
        }

        .sidebar-right .sidebar-right-trigger {
            display: none !important;
        }

        .dz-demo-panel .dz-demo-trigger {
            display: none !important;
        }
    </style>
</head>

<body>


    <div class="main-wrapper">
        {{-- header start --}}
        @include("admin.components.header")
        {{-- sidebar start --}}
        @include("admin.components.sidebar")
        {{-- content --}}
        @yield("content")
        {{-- copyright --}}
        @include("admin.components.copyright")
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Required vendors -->
    <script src="{{ url('vendor/global/global.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ url('vendor/chart.js/chart.bundle.min.js') }}"></script>
    <script src="{{ url('vendor/owl-carousel/owl.carousel.js') }}"></script>

    <!-- Apex Chart -->
    <script src="{{ url('vendor/apexchart/apexchart.js') }}"></script>

    <!-- Dashboard 1 -->
    <script src="{{ url('js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ url('js/custom.min.js') }}"></script>
    <script src="{{ url('js/deznav-init.js') }}"></script>
    <script src="{{ url('js/demo.js') }}"></script>
    {{--
    <script src="{{ url('js/styleSwitcher.js') }}"></script> --}}
    {{-- summernote js --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>

    <!-- Datatable -->
    <script src="{{ url('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/js/dataTables.responsive.min.js') }}"></script>
   
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    {{-- for the push script of the pages --}}
    @stack('scripts')
    <script>
    $('.datatable').DataTable();
        // for description to adjust in cell
        document.addEventListener("DOMContentLoaded", function () {
            const descCell = document.getElementById("content");
            if (descCell) {
                descCell.style.wordWrap = "break-word";
                descCell.style.overflowWrap = "break-word";
                descCell.style.whiteSpace = "normal";
            }
        });

        function assignedDoctor() {
            /*  testimonial one function by = owl.carousel.js */
            jQuery(".assigned-doctor").owlCarousel({
                loop: false,
                margin: 30,
                nav: true,
                autoplaySpeed: 3000,
                navSpeed: 3000,
                paginationSpeed: 3000,
                slideSpeed: 3000,
                smartSpeed: 3000,
                autoplay: false,
                rtl: true,
                dots: false,
                navText: [
                    '<i class="fa fa-caret-left"></i>',
                    '<i class="fa fa-caret-right"></i>',
                ],
                responsive: {
                    0: {
                        items: 1,
                    },
                    576: {
                        items: 2,
                    },
                    767: {
                        items: 3,
                    },
                    991: {
                        items: 2,
                    },
                    1200: {
                        items: 3,
                    },
                    1600: {
                        items: 4,
                    },
                    1920: {
                        items: 5,
                    },
                },
            });
        }

        jQuery(window).on("load", function () {
            setTimeout(function () {
                assignedDoctor();
            }, 1000);
        });

        //+++++++++++++++++++++++++++++++++++++++++++++++
        // FOR SUMMERNOTE
        $(function () {
            $('#place').summernote({
                placeholder: 'Write place details here...',
                tabsize: 2,
                height: 200,           // editor height in px

            });
            $('#address').summernote({
                placeholder: 'Write place details here...',
                tabsize: 2,
                height: 200,           // editor height in px

            });
            $('#aboutCompany').summernote({
                placeholder: 'Write place details here...',
                tabsize: 2,
                height: 200,           // editor height in px

            });
            // $('#subparameterDexcription').summernote({
            //     placeholder: 'Write details here...',
            //     tabsize: 2,
            //     height: 200,           // editor height in px

            // });
        });
        // FUNCTIONS TO CREATE SLUG
        function generateSlug(title) {
            return title
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .trim()
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-'); // Replace multiple hyphens with a single hyphen
        }
        // Auto-generate slug for Add modal
        $('#add-title').on('input', function () {
            var title = $(this).val();
            var slug = generateSlug(title);
            $('#add-slug').val(slug);
        });
        $('#edit_title').on('input', function () {
            var title = $(this).val();
            var slug = generateSlug(title);
            $('#edit_slug').val(slug);
        });

        // FOR PACKAGES CRUD 
        $(document).on('click', '.viewPackage', function () {
            var id = $(this).data(id);
            $.ajax({
                url: "{{ url('/admin-packages/view') }}/" + id,
                type: "GET",
                success: function (packages) {
                    $('#v_title').text(packages.title);
                    $('#v_slug').text(subparameter.slug);
                    $('#v_status').text(subparameter.status == 'active' ? 'Active' : 'Inactive');
                    $('#v_description').text(subparameter.description);
                    $('#v_parameter').text(subparameter.parameter_title);
                    // open modal
                    $('#viewPackage').modal('show');
                }

            });
        });





        $(document).on('click', '.viewSeoSetting', function () {

            var id = $(this).data('id');

            $.ajax({
                url: "{{ url('/seo-setting/view') }}/" + id,
                type: "GET",
                success: function (seo) {

                    // Fill modal data
                    $('#v_title').text(seo.title);
                    $('#v_page').text(seo.page);
                    $('#v_description').text(seo.description);
                    $('#v_keywords').text(seo.keywords);


                    // Open modal
                    $('#viewAppointment').modal('show');
                }
            });

        });
        $(document).on('click', '.editSeoSetting', function () {

            var id = $(this).data('id');

            $.ajax({
                url: "{{ url('/seo-setting/view') }}/" + id,
                type: "GET",
                success: function (seo) {

                    $('#edit_id').val(seo.id);
                    $('#edit_title').val(seo.title);
                    $('#edit_page').val(seo.page);
                    $('#edit_description').val(seo.description);
                    $('#edit_keywords').val(seo.keywords);


                    $('#editAppointment').modal('show');
                }
            });
        });
        $('#editDoctorForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ url('/seo-setting/update') }}",
                data: formData,
                contentType: false,
                processData: false,

                success: function (response) {
                    Swal.fire("Updated!", "Seo Setting updated successfully!", "success");
                    $('#editAppointment').modal('hide');
                    location.reload();
                }
            });

        });
        $(document).on("click", ".deleteSeoSetting", function () {

            let id = $(this).data("id");
            let row = $(this).closest("tr");

            Swal.fire({
                title: "Are you sure?",
                text: "This SEO will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('/seo-setting/delete') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {

                            Swal.fire("Deleted!", "Seo Setting removed successfully.", "success");

                            // remove row
                            row.fadeOut(600, function () {
                                $(this).remove();
                            });
                        }
                    });

                }
            });

        });




    </script>
</body>

</html>