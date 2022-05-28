@if( is_null(Auth::user()->membership_id) )

<div id="kt_alert" class="">

    <!-- <div class="container d-flex align-items-stretch justify-content-between"> -->
        <div class="alert alert-custom alert-danger fade show" role="alert">
            <div class="container d-flex align-items-stretch justify-content-between">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">
                    You are trial for 3 days!
                  
                </div>
 
                <a href="{{ Route('membership')}}" class="btn btn-hover-transparent-white font-weight-bold font-size-h5"><i class="flaticon2-shopping-cart-1"></i>BUY</a>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>
        </div>
    <!-- </div> -->
</div>
@elseif(Auth::user()->membership_end_date < date('Y-m-d'))
<div id="kt_alert" class="">

    <!-- <div class="container d-flex align-items-stretch justify-content-between"> -->
        <div class="alert alert-custom alert-danger fade show" role="alert">
            <div class="container d-flex align-items-stretch justify-content-between">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">
                    Membership is expired! Please purchase membership
                  
                </div>
                <a href="{{ Route('membership')}}" class="btn btn-hover-transparent-white font-weight-bold font-size-h5"><i class="flaticon2-shopping-cart-1"></i>BUY</a>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>
        </div>
    <!-- </div> -->
</div>
@endif
