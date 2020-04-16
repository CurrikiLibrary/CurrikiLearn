@extends('layouts.resource_view')

@section('content')

	<div class="h-100 d-flex flex-column">
	    <div class="resource-view-top">
			<img class="resource-view-brand" src="{{ asset('images/logo.jpg') }}" alt="{{ env('APP_NAME') }} Curriculum Writing Projects">
			@if(URL::previous() == URL::current())
				<a class="btn btn-primary btn-back resource-back-btn" href="{{url('/')}}">Back</a>
			@elseif(strpos(URL::previous(), '/create') !== false)
				<a class="btn btn-primary btn-back resource-back-btn" href="{{url("/resource_management/{$resource->resourceid}/edit")}}">Back</a>
			@else
				<a class="btn btn-primary btn-back resource-back-btn" href="{{URL::previous()}}">Back</a>
			@endif
	    </div>
	    <div class="resource-view-content flex-grow-1">

 			<iframe src="{{ env('CURRIKI_API_URL') }}/oer/{{$resource->pageurl}}?oer-only=true"></iframe>   	

	    </div>
	</div>
@endsection

@section('styles')
 <style>
 	html, body {
		height:100%;
	}
 	iframe {
 		border:0;
 		height:100%;
 		width:100%;
 	}
 	.resource-view-top {
 		height:10%;
 		padding:5px;
 	}
 	.resource-view-content {
 		height:89%;
 	}
 	.resource-view-brand {
 		position:relative;
 		left:25px;
 		height:100%;
 	}
 	.resource-view-top a {
 		position:absolute;
 		right:25px;
 	}
 	.resource-back-btn {
 		background-color:#607a9b;
 		border-color: #607a9b;
 	}
	.btn-back {
		min-width: 90px;
	}
	.btn-back:before {
		content: "";
		display: inline-block;
		width: 0;
		height: 0;
		margin-bottom: .125rem;
		vertical-align: middle;
		border: solid #fff;
		border-width: 0 1px 1px 0;
		padding: 5px;
	}

	.btn-back:before {
		margin-right: .5rem;
		transform: rotate(135deg);
		-webkit-transform: rotate(135deg);
	}
 </style>
@endsection