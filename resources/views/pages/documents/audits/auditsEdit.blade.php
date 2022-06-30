@extends('layout.default')

@section('content')
<div class="body">
    <div  class="d-flex flex-column-fluid body">
        <div class="{{ Metronic::printClasses('content-container', false) }}">
            <div class="row">
                <div class="col-md-12">
                    <button id="tempBtn" style="z-index: 1000; position: absolute;bottom: 10px; left: 10px; display: none;" class="btn btn-icon btn-primary btn-circle btn-lg font-weight-bold font-size-h3 px-5 py-5" onclick="showTemplates();">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect>
                                    <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                    </button>
                    @if ($message = Session::get('error'))
                    <div class="alert alert-custom alert-notice alert-light-danger mt-20 fade show" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">{!! $message !!}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>
                    <?php Session::forget('error');?>
                    @endif
                    <div id="template_board" class="d-flex flex-column justify-content-center align-item-center" style="z-index: 1000;">
                        <div class="mt-20">
                            <h1 class="mb-5 text-center">Audit Forms</h1>
                            <div class="row">

                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">H&S CDM Checklist</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSdJKX-yBRYTKhNjpgT-6D37VP-rLNvW4TLgYEJlQSg2mY4tdQ/viewform?embedded=true`);" ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">H&S Fire inspection</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSfCgBTu1c3HTkk1mS58Bj0fFgtFnKU3j_oQA2gxDkKq2qybpg/viewform?embedded=true`);"  ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Stage H&S Audit</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSf7osdx8rxUa-fnbPi0f8mltLtHjZIvQDIlH1pwUq87pHCk9Q/viewform?embedded=true`);"  ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Workshop H&S Audit</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSesO_wvX6KTeWmwihEztApLdy3ZE4sPYjQVXCw3NcTrmFzrsg/viewform?embedded=true`);"  ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Office H&S Audit</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSeaSDiD8aP-xLa_NLGGtE2eOkCMhXgNk-fBBMnEIyw4rRgoLQ/viewform?embedded=true`);"  ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Ancillary Building H&S Audit</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSdAeiwxGqTVA_TU1vtGthBzMUEaVStdpXEGDzCom9EEVg72fg/viewform?embedded=true`);"  ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Fire Audit</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLScU19nXmTMtXwgIWiUCeJt3oawgFqr3Wjg8k_Rn9ZhNCYcZ1Q/viewform?embedded=true`);" ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Production Drone Request</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="https://docs.google.com/forms/d/e/1FAIpQLScApNnV_wdGsCR1f62DnXvgJU-9M7D0DR7v42gLGvaUrO9zvA/viewform?usp=sf_link" target="_blank" class="btn font-weight-bold btn-primary btn-shadow" ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                          

                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">Daily Set & Location Safety Brief</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSe56fKrGrnjTIDvBfY3JyM0aNCYWnUIImFl9rpICQ8SSmAMQg/viewform?embedded=true`);" ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    </div>

                    <div id="form_board" class="mt-5">
                        <iframe id="form_frame" src="https://docs.google.com/forms/d/e/1FAIpQLSdJKX-yBRYTKhNjpgT-6D37VP-rLNvW4TLgYEJlQSg2mY4tdQ/viewform?embedded=true" width="100%" height="100%" style="overflow-y: auto;" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css">
<link rel="stylesheet" href="/css/pages/documents/styles.css">
<link rel="stylesheet" href="/css/pages/documents/pdfannotate.css">
<link rel="stylesheet" href="/css/pages/documents/component.css">
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/js/pages/apps/documents/audits.js"></script>
@endsection

