@extends('layouts.email')

@section('content')
<div class="container" style="background-color: white;">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
            	<div class="card" style="background-color: #3b3f48;padding-top: 30px;padding-bottom: 30px;">
            		<div id="email_icon">
            			<center >
            				<img width="25px;" height="25px;" src="http://within.id8tr.com/icon_email.png">
            			</center> 
            		</div>

            		 <div class="card-header" style="font-size: 25px; color: white;" >Security Code</div>

            	</div>
               	
               	<div class="card" style="color: white;padding-top: 20px;padding-bottom: 20px; background-color: #101010f0;font-family: arial;">
               		<div class="card-body" style = "color:white;">
	                   If you've lost your password or wish to reset it, user the code below to get started
	                </div>
	                <br>
	                <br>
	                <div class="card-body" style="font-weight: bold;font-size: 35px;color:white">
	                  {{$code}}
	                </div>
	                 <br>
	                <br>

	                 <div class="card-body" style = "color:white;">
	                  If you did not request a password reset, you can safely ignore this email. Only a person with access to your email can reset your account password.
	                </div>
               	</div>

                

            </div>
        </div>
    </div>
</div>
@endsection
