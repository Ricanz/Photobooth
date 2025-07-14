@stack('script')

<script>var hostUrl = "app_template/";</script>
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="{{asset('app_template/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('app_template/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Page Vendors Javascript(used by this page)-->
<script src="{{asset('app_template/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<script src="{{asset('app_template/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<!--end::Page Vendors Javascript-->
<!--begin::Page Custom Javascript(used by this page)-->
<script src="{{asset('app_template/js/custom/widgets.js')}}"></script>
<!--end::Page Custom Javascript-->
@yield('scripts');