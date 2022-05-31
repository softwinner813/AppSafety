@extends('layout.default')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="{{ Metronic::printClasses('content-container', false) }}">
        <!--begin::Profile Personal Information-->
        <div class="d-flex flex-row">
            <!--begin::Content-->
            <div class="flex-row-fluid ml-lg-8">
                <div class="card card-custom">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Membership Subscription Result
                                <!-- <span class="d-block text-muted pt-2 font-size-sm">Monthly Checkout</span> -->
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">       
                            <div class="col-md-12 col-md-offset-2">          
                                @if ($message = Session::get('success'))
                                <div class="alert alert-custom alert-notice alert-light-success fade show mb-5" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">{!! $message !!}</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                                <?php Session::forget('success');?>
                                <a href="/" class="text-primary font-size-h6"><i class="fas fa-arrow-left text-primary"></i>&nbsp; Go To Home</a>
                                @endif

                                @if ($message = Session::get('error'))
                                <div class="alert alert-custom alert-notice alert-light-danger fade show mb-5" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">{!! $message !!}</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                                <?php Session::forget('error');?>
                                <a href="/setting/membership" class="text-primary font-size-h6"><i class="fas fa-arrow-left text-primary"></i>&nbsp; Go To Back</a>
                                @endif

                            </div>
                        </div>
                    </div>
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

<!--end::Page Scripts-->
@endsection