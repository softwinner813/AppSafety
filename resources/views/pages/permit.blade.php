@extends('layout.default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<button onclick="demoFromHTML()" class="btn btn-primary">PDF</button>
		<div id="pspdfkit" style="height: 100vh"></div>
	</div>
</div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="/assets/pspdfkit.js"></script>
<script>
    PSPDFKit.load({
		container: "#pspdfkit",
  		document: "/test.pdf" // Add the path to your document here.
	})
	.then(function(instance) {
		console.log("PSPDFKit loaded", instance);
	})
	.catch(function(error) {
		console.error(error.message);
	});
</script>
@endsection