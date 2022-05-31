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
                            <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Change your password for more security</span>
                        </div>
                        <div class="card-toolbar">
                            <button type="submit" class="btn btn-success mr-2" id="changepass_submit">Save Changes</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form" method="POST" id="changepass_form" action="{{ route('changepass-save') }}">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if($errors)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mt-10 mb-6">Current Password</h5>
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Current Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-key"></i>
                                            </span>
                                        </div>
                                        <input class="form-control form-control-lg form-control-solid" type="password" name="current-password" placeholder="Current Password" required />

                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mt-10 mb-6">New Password</h5>
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('new-password') ? ' has-error' : '' }}">
                                <label class="col-xl-3 col-lg-3 col-form-label">New Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-key"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg form-control-solid" name="new-password" placeholder="New Password" required />
                                       
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-xl-3 col-lg-3 col-form-label">Confirm Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-key"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg form-control-solid" placeholder="Confirm Password" name="new-password_confirmation" required />
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
<script src="/js/pages/apps/changepassword.js"></script>
<!--end::Page Scripts-->
@endsection