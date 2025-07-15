<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
    <!--begin::Form-->
    <form id="kt_modal_add_frame_form" class="form" enctype="multipart/form-data" method="POST">
        <!--begin::Scroll-->
        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_frame_scroll" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
            data-kt-scroll-dependencies="#kt_modal_add_frame_header" data-kt-scroll-wrappers="#kt_modal_add_frame_scroll"
            data-kt-scroll-offset="300px">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="d-block fw-bold fs-6 mb-5">Image</label>
                <!--end::Label-->
                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true"
                    style="background-image: url('{{ asset('app_template/media/svg/avatars/blank.svg') }}')">
                    <!--begin::Preview existing avatar-->
                    <div class="image-input-wrapper w-125px h-125px"
                        style="background-image: url({{ asset('app_template/media/avatars/150-1.jpg') }});">
                    </div>
                    <!--end::Preview existing avatar-->
                    <!--begin::Label-->
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                        <i class="bi bi-pencil-fill fs-7"></i>
                        <!--begin::Inputs-->
                        <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                        <input type="hidden" name="image" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Label-->
                    <!--begin::Cancel-->
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                        <i class="bi bi-x fs-2"></i>
                    </span>
                    <!--end::Cancel-->
                    <!--begin::Remove-->
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
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
                    placeholder="Title..." value="" />
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
                    placeholder="Type..." value="" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-bold fs-6 mb-2">Border Top</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" name="border_top" class="form-control form-control-solid mb-3 mb-lg-0"
                    placeholder="Border Top..." value="" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-bold fs-6 mb-2">Border Bottom</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" name="border_bottom" class="form-control form-control-solid mb-3 mb-lg-0"
                    placeholder="Border Bottom..." value="" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-bold fs-6 mb-2">Border Right</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" name="border_right" class="form-control form-control-solid mb-3 mb-lg-0"
                    placeholder="Border Right..." value="" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-bold fs-6 mb-2">Border Left</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" name="border_left" class="form-control form-control-solid mb-3 mb-lg-0"
                    placeholder="border_left..." value="" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Scroll-->
        <!--begin::Actions-->
        <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3" data-kt-frames-modal-action="cancel">Discard</button>
            <button type="submit" class="btn btn-primary" data-kt-frames-modal-action="submit">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
