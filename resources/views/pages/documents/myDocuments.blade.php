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
                            <a href="{!! Route('document.edit', $type) !!}" class="btn btn-primary font-weight-bolder">
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
                                    <td >
                                        @if(!$doc->status)
                                        <button class="btn btn-light-success" onclick="deleteEmail(`{{$doc->id}}`)"><i class="fa fa-link"></i> Share</button>
                                        @else
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline"></span>
                                        @endif
                                    </td>
                                    <td class="col-md-2">
                                        <button class="btn btn-light-danger" onclick="deleteDoc(`{{$doc->id}}`)"><i class="fa fa-trash"></i></button>
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

<!-- Modal-->
<div class="modal fade" id="exampleModalCustomScrollable" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form class="form" id="kt_add_email_form"  method="POST" action="{{ route('employee-save') }}">
                    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Email Dialog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                  
                        <div class="form-group">
                            <input class="form-control h-auto text-white placeholder-white bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5 "  placeholder="Email" id="email" type="email" name="email"  required autocomplete="email" autofocus />
                          
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="add_btn">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/js/pages/apps/documents/documentTable.js"></script>

<!--end::Page Scripts-->
@endsection