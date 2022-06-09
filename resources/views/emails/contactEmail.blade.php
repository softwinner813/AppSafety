@extends('layouts.email')

@section('content')

<div class="u-row-container" style="padding: 0px;background-color: transparent">
	<div class="u-row"
	    style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #f0f7ff;">
	    <div
	        style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
	        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #f0f7ff;"><![endif]-->

	        <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
	        <div class="u-col u-col-100"
	            style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
	            <div style="width: 100% !important;">
	                <!--[if (!mso)&(!IE)]><!-->
	                <div
	                    style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
	                    <!--<![endif]-->

	                    <table style="font-family:arial,helvetica,sans-serif;" role="presentation"
	                        cellpadding="0" cellspacing="0" width="100%" border="0">
	                        <tbody>
	                            <tr>
	                                <td class="v-container-padding-padding"
	                                    style="overflow-wrap:break-word;word-break:break-word;padding:20px 10px 5px 20px;font-family:arial,helvetica,sans-serif;"
	                                    align="left">

	                                    <h1 class="v-text-align"
	                                        style="margin: 0px; line-height: 140%; text-align: left; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 19px;">
	                                        Hello, How are you?,
	                                    </h1>

	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>

	                    <table style="font-family:arial,helvetica,sans-serif;" role="presentation"
	                        cellpadding="0" cellspacing="0" width="100%" border="0">
	                        <tbody>
	                            <tr>
	                                <td class="v-container-padding-padding"
	                                    style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 10px 20px;font-family:arial,helvetica,sans-serif;"
	                                    align="left">

	                                    <div class="v-text-align"
	                                        style="color: #6b6b6b; line-height: 140%; text-align: left; word-wrap: break-word;">
	                                        <p style="font-size: 14px; line-height: 140%;"><span
	                                                style="font-family: Lato, sans-serif;">New
	                                                contact request is received.</span></p>
	                                    </div>

	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>

	                    <table style="font-family:arial,helvetica,sans-serif;" role="presentation"
	                        cellpadding="0" cellspacing="0" width="100%" border="0">
	                        <tbody>
	                            <tr>
	                                <td class="v-container-padding-padding"
	                                    style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 30px;font-family:arial,helvetica,sans-serif;"
	                                    align="left">

	                                    <div class="v-text-align" align="center">
	                                    	<p style="margin-bottom: 10px;">Name: {{$name}}</p>
	                                    	<p style="margin-bottom: 10px;">Company: {{$company}}</p>
	                                    	<p style="margin-bottom: 10px;">Email: {{$email}}</p>
	                                    	<p style="margin-bottom: 10px;">Phone: {{$phone}}</p>
	                                    	<p style="margin-bottom: 10px;">{{$comment}}</p>
	                                    </div>

	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>

	                    <table style="font-family:arial,helvetica,sans-serif;" role="presentation"
	                        cellpadding="0" cellspacing="0" width="100%" border="0">
	                        <tbody>
	                            <tr>
	                                <td class="v-container-padding-padding"
	                                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 10px 10px 20px;font-family:arial,helvetica,sans-serif;"
	                                    align="left">

	                                    <div class="v-text-align"
	                                        style="color: #6b6b6b; line-height: 140%; text-align: left; word-wrap: break-word;">
	                                        <p
	                                            style="font-size: 14px; line-height: 140%; text-align: center;">
	                                            <a rel="noopener" href="fsdafdsafdas"
	                                                target="_blank">
	                                            </a></p>
	                                    </div>

	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>

	                    <!--[if (!mso)&(!IE)]><!-->
	                </div>
	                <!--<![endif]-->
	            </div>
	        </div>
	        <!--[if (mso)|(IE)]></td><![endif]-->
	        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
	    </div>
	</div>
</div>





@endsection
