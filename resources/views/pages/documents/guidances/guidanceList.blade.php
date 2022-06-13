@extends('layout.default')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="{{ Metronic::printClasses('content-container', false) }}">
        <!--begin::Profile Personal Information-->
        <div class="row">

            <!--begin::Content-->
            <div class="col-md-12">
                <!--begin::Card-->
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Documents List
                            <span class="d-block text-muted pt-2 font-size-sm">You can add or remove document to share with your employees</span></h3>
                        </div>
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <a href="{!! Route('document.guidance.edit') !!}" class="btn btn-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>Add</a>
                            <!--end::Button-->
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Search Form-->
                        <!--begin::Search Form-->
                        <div class="mb-7">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-xl-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-12 my-2 my-md-0">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Search..." id="kt_datatable_search_query" />
                                                <span>
                                                    <i class="flaticon2-search-1 text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                    <a href="#" class="btn btn-light-primary px-6 font-weight-bold">Search</a>
                                </div>
                            </div>
                        </div>
                        <!--end::Search Form-->
                        <!--end: Search Form-->
                        <!--begin: Datatable-->
                        <table class="datatable datatable-bordered datatable-head-custom datatable datatable-bordered datatable-head-custom datatable-default datatable-primary " id="kt_datatable">
                            <thead>
                                <tr>
                                    <th title="Field #1">#No</th>
                                    <th class="col-md-6 text-center" title="Field #2">Document Name</th>
                                    <th class="col-md-6 text-center" title="Field #2">To</th>
                                    <th class="col-md-6 text-center" title="Field #2">Created</th>
                                    <th title="Field #3">Status</th>
                                    <th title="Field #4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $key => $doc) 
                                <tr>
                                    <td class="col-md-2">{{ $key + 1 }}</td>
                                    <td>
                                        <a href="/{{$doc->file}}" target="_blank" >
                                            {{  $doc->name }}    
                                        </a>
                                    </td>
                                    <td>{{ $doc->to}}</td>
                                    <td>{{ $doc->created_at }}</td>
                                    <td >
                                        @if($doc->status == 1)
                                        <span class="label label-danger label-inline font-weight-lighter text-white text-center"><i class="far fa-envelope text-white font-size-sm"></i>&nbsp; Signing</span>
                                        @elseif($doc->status == 2)
                                        <span class="label label-primary label-inline font-weight-lighter text-white text-center"><i class="fas fa-check text-white font-size-sm"></i>&nbsp;Completed</span>
                                        @endif
                                    </td>
                                    <td class="col-md-2">
                                        <button title="Resend" class="btn btn-light-primary btn-sm" onclick="resendDoc(`{{$doc->id}}`)"><i class="fab fa-telegram-plane"></i></button>
                                        <button title="Delete" class="btn btn-light-danger btn-sm" onclick="deleteDoc(`{{$doc->id}}`)"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Personal Information-->
    </div>
</div>

@include('layout.partials.extras._progressModal')

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/js/pages/apps/documents/guidance/guidanceTable.js"></script>

<!--end::Page Scripts-->
@endsection