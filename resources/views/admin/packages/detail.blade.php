<x-app-layout>
    <!--begin::Toolbar-->
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-dark fw-bolder my-1 fs-3">Detail Package</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 text-hover-primary">Home</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">Package Management</li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-500">Detail Package</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="content flex-column-fluid" id="kt_content">
        <div class="card">
            <div class="body  mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="kt_edit_package_form" class="form" enctype="multipart/form-data" method="POST"
                    data-action="{{ route('package.update', $data->id) }}">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="d-block fw-bold fs-6 mb-5">Image</label>
                        <!--end::Label-->
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true"
                            style="background-image: url('{{ asset('storage/' . $data->image) }}')">
                            <!--begin::Preview existing image-->
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url({{ asset('storage/' . $data->image) }});">
                            </div>
                            <!--end::Preview existing image-->
                            <!--begin::Label-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <!--begin::Inputs-->
                                <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="image" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->
                            <!--begin::Cancel-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel image">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <!--end::Cancel-->
                            <!--begin::Remove-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <!--end::Remove-->
                        </div>
                        <!--end::Image input-->
                        <!--begin::Hint-->
                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                        <!--end::Hint-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Title</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="title" class="form-control form-control-solid mb-3 mb-lg-0"
                            placeholder="Title..." value="{{ $data->title }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Description</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="description" class="form-control form-control-solid mb-3 mb-lg-0"
                            placeholder="Description..." value="{{ $data->description }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Price</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="price" class="form-control form-control-solid mb-3 mb-lg-0"
                            placeholder="Price..." value="{{ $data->price }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Total Print</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="total_print" class="form-control form-control-solid mb-3 mb-lg-0"
                            placeholder="Total Print..." value="{{ $data->total_print }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-bold fs-6 mb-2">Type</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="type" class="form-control form-control-solid mb-3 mb-lg-0"
                            placeholder="Type..." value="{{ $data->type }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3">Back</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>

        </div>
    </div>
    <!--end::Post-->

</x-app-layout>

<script src="{{ asset('app_template/js/custom/apps/frame/edit.js') }}"></script>
@push('scripts')
