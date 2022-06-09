@extends('layout.default')

@section('content')
<div class="body">
    <div  class="d-flex flex-column-fluid body">
        <div class="{{ Metronic::printClasses('content-container', false) }}">
            <div class="row">
                <div class="col-md-12">
                    <div id="tempBtn"  class="mt-5" style="z-index: 1000;">
                        <button class="btn btn-primary" onclick="showTemplates();"><i class="far fa-file-alt"></i> Choose Template</button>
                    </div>
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
                                                <h6 class="text-dark pt-5">Daily Safety Brief to Cast & Crew - Film & Production</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSe56fKrGrnjTIDvBfY3JyM0aNCYWnUIImFl9rpICQ8SSmAMQg/viewform?embedded=true`);" ><i class="fas fa-edit"></i> Edit</a>
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
                                                <h6 class="text-dark pt-5">H&S Fire Checklist - Film & Production 'Master'</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn font-weight-bold btn-primary btn-shadow" onclick="selectTemplate(`https://docs.google.com/forms/d/e/1FAIpQLSfCgBTu1c3HTkk1mS58Bj0fFgtFnKU3j_oQA2gxDkKq2qybpg/viewform?embedded=true`);" ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-5">
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0 bg-secondary">
                                            <div class="overlay-wrapper text-danger py-10 px-5 d-flex flex-column align-items-center justify-content-center">
                                                <i class="fas fa-file-alt text-danger" style="font-size: 100px;"></i>
                                                <h6 class="text-dark pt-5">H&S inspection audit</h6>
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="https://docs.google.com/forms/d/12rPtQAlObgz4Ga8-JgKMQBycIrxYn7nP1ATyYMZZSRU/edit?usp=sharing" class="btn font-weight-bold btn-primary btn-shadow" ><i class="fas fa-edit"></i> Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="form_board" class="mt-5">
                        <iframe id="form_frame" src="https://docs.google.com/forms/d/e/1FAIpQLSfCgBTu1c3HTkk1mS58Bj0fFgtFnKU3j_oQA2gxDkKq2qybpg/viewform?usp=sf_link/viewform?embedded=true" width="100%" height="800" style="overflow-y: auto;" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
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

