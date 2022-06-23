@extends('layout.default')

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="{{ Metronic::printClasses('content-container', false) }}">
        <h1 class="mt-10 text-white" style="font-size: 40px;">{{$page_title}}</h1>
        <h6 class="text-white mb-5 font-weight-lighter" style="font-size: 20px;">{{$page_description}}</h6>
        <div class="row">
            <div class="col-md-8 p-15  bg-white">
                <form action="{!! Route('contact.send') !!}" method="POST" id="contact_form">
                    <h1 style="font-size: 30px; color: #126bca; ">Send your request</h1>
                    <div class="form-group row fv-plugins-icon-container mt-10">
                        <div class="col-lg-6">
                            <label class="font-size-h5">* Full Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Full Name" value="" required autocomplete="name" autofocus>
                            <div class="fv-plugins-message-container"></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="font-size-h5">* Phone Number:</label>
                            <input type="text" name="phonenumber" class="form-control" placeholder="+1 (312) 342 2345" value="" required autocomplete="phonenumber" autofocus>
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>
                    <div class="form-group row fv-plugins-icon-container">
                        <div class="col-lg-6">
                            <label class="font-size-h5">* E-mail:</label>
                            <input type="email" name="email" class="form-control" placeholder="appsafely@sale.com" value="" required autocomplete="email">
                            <div class="fv-plugins-message-container"></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="font-size-h5">* Company:</label>
                            <input type="text" name="company" class="form-control" placeholder="AppSafely" value="" required autocomplete="company">
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>
                    <div class="form-group row fv-plugins-icon-container">
                        <div class="col-lg-12">
                            <label class="font-size-h5">* Comments:</label>
                            <textarea class="form-control" name="comment" placeholder="Enter your comment" rows="5" required autocomplete="comment"></textarea>
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" id="sendBtn" class="btn btn-primary font-weight-bold btn-pill font-size-h6 px-10 py-4 mr-2">SUBMIT 
                            <span class="svg-icon svg-icon-white "><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Send.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M3,13.5 L19,12 L3,10.5 L3,3.7732928 C3,3.70255344 3.01501031,3.63261921 3.04403925,3.56811047 C3.15735832,3.3162903 3.45336217,3.20401298 3.70518234,3.31733205 L21.9867539,11.5440392 C22.098181,11.5941815 22.1873901,11.6833905 22.2375323,11.7948177 C22.3508514,12.0466378 22.2385741,12.3426417 21.9867539,12.4559608 L3.70518234,20.6826679 C3.64067359,20.7116969 3.57073936,20.7267072 3.5,20.7267072 C3.22385763,20.7267072 3,20.5028496 3,20.2267072 L3,13.5 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span>
                        </button>
                    </div>

                </form>
            </div>            
            <div class="col-md-4 p-15" style="background-color: #d9e3fd;">
                <h1 class="mb-10 text-center " style="font-size: 30px; color: #126bca;">Contact Information</h1>
                <div class="font-size-h5 mt-20 " st >
                    <label>Address: &nbsp;&nbsp;</label>
                    <label style="color: #126bca; ">1 Dunraven Dr, Newport, NP10 8HS</label>
                </div>
                <div class="font-size-h5 mt-20 " >
                    <label>Phone: &nbsp;&nbsp;</label>
                    <label style="color: #126bca;">+44 078 2577 8944</label>
                </div>

                <div class="font-size-h5 mt-20 " >
                    <label>E-mail: &nbsp;&nbsp;</label>
                    <label style="color: #126bca;">Info@appsafely.co.uk</label>
                </div>
                <p class="mt-20">
                    <a href="#" class="btn btn-icon btn-circle btn-facebook mr-2">
                        <i class="socicon-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-icon btn-circle btn-twitter mr-2">
                        <i class="socicon-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-icon btn-circle btn-instagram mr-2">
                        <i class="socicon-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-icon btn-circle btn-youtube mr-2">
                        <i class="socicon-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-icon btn-circle btn-linkedin mr-2">
                        <i class="socicon-linkedin"></i>
                    </a>
                    <a href="#" class="btn btn-icon btn-circle btn-skype mr-2">
                        <i class="socicon-skype"></i>
                    </a>
                </p>
            </div>            
        </div>
    </div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('/js/pages/apps/contact.js')}}"></script>
@endsection