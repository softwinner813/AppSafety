@extends('layout.default')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="{{ Metronic::printClasses('content-container', false) }}">
        <!--begin::Profile Personal Information-->
        <div class="d-flex flex-row">
            @include('pages.settings.aside')

            <!--begin::Content-->
            <div class="flex-row-fluid ml-lg-8">
                <!--begin::Card-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Company Information</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Update your company informaiton</span>
                        </div>
                        <div class="card-toolbar">
                            <button type="submit" class="btn btn-success mr-2" id="profile_submit">Save Changes</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form" method="POST" id="profile_form" action="{{ route('profile-save') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Company Info</h5>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Company Logo</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-image: url(/media/users/blank.png)">
                                        @if(Auth::user()->logo)
                                        <div class="image-input-wrapper" style="background-image: url({{Auth::user()->logo}})"></div>
                                        @else
                                        <div class="image-input-wrapper" style="background-image: url(/media/users/blank.png)"></div>
                                        @endif
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change logo">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"  />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel logo">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove logo">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                    </div>
                                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Company Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" value="{{ Auth::user()->name }}" name="name" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mt-10 mb-6">Contact Info</h5>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Phone Number</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-phone"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="phonenumber"  value="{{ Auth::user()->phonenumber}}" placeholder="Phone" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Company Address</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-map"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ Auth::user()->address }}" placeholder="Company Address" name="address" />
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                        <!--end::Body-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Personal Information-->
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/js/jquery.form.js"></script> 
<script src="/js/pages/apps/profile.js"></script>
<!--end::Page Scripts-->
@endsection