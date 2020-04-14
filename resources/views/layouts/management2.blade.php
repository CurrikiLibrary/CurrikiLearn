<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ env('APP_NAME') }} Curriculum Writing Projects</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind+Siliguri:400,600,700&display=swap">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
	@yield('styles')
</head>
<body>
	<nav class="navbar navbar-primary navbar-expand-sm">
		<div class="container">
			<a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('images/' . (env('NASSAU_HUB_ID') == 1 ? 'logo_boces.jpg' : 'NEAF-logo-cmyk.jpg'))}}" width="172" height="66" alt="{{ env('APP_NAME') }} Curriculum Writing Projects"></a>
			<div class="navbar-collapse collapse show">
				<ul class="navbar-nav mx-auto mr-sm-0">
					<li class="nav-item dropdown">
						<a class="btn btn-nav dropdown-toggle" href="#" role="button" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account</a>
						<div class="dropdown-menu dropdown-menu-right user-block" aria-labelledby="navbarDropdownMenuLink">
							<div class="user-block-top">
								<img class="user-photo" src="{{ strlen(Auth::user()->uniqueavatarfile) == 0 ? asset('images/user_avatar.png') : env('CURRIKI_AVATARS_BASE_URL').Auth::user()->uniqueavatarfile}}" width="103" height="103">
								<div class="user-info">
									<span class="user-name">{{ Auth::user()->full_name  }}</span>
									<dl class="user-horizontal">
										<dt>Organization:</dt>
										<dd>{{ Auth::user()->organization ? Auth::user()->organization : 'N/A' }}</dd>
										<dt>Language:</dt>
										<dd>{{ Auth::user()->language ? Auth::user()->languageName->displayname : 'N/A' }}</dd>
										<dt>Joined:</dt>
										<dd>{{ Auth::user()->registerdate ? Auth::user()->registerdate->format('M d, Y g:i a') : '' }}</dd>
										<dt>Last Activity:</dt>
										<dd>{{ Auth::user()->logins->count() > 0 ? Auth::user()->logins->max('logindate')->format('M d, Y g:i a') : '' }}</dd>						
									</dl>
								</div>
								<a class="btn user-edit" data-toggle="modal" href="#userEditModal"><i class="fa fa-pencil"></i></a>
							</div>
							<ul class="user-menu">
								<li><a href="{{url('/resource_management')}}"><i class="fa fa-user-o"></i> My Account</a></li>
								<li><a href="{{url('/resource_management')}}"><i class="fa fa-file-o"></i> Manage Resources</a></li>
								@if(Auth::user()->getAdminGroups()->isEmpty() == false)
								<li><a href="{{url('/group_management')}}"><i class="fa fa-user-md"></i> Manage Membership</a></li>
								<li><a href="{{url('/user_management')}}"><i class="fa fa-users"></i> Manage Users</a></li>
								<li><a href="{{url('/reports')}}"><i class="fa fa-line-chart"></i> Reports</a></li>
								@endif
								<li class="li-footer"><a href="{{url('/logout_api')}}"><i class="fa fa-sign-out"></i> Logout</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<main class="main">
		<div class="container py-5">
			@if ($errors->any())
			<div class="container">
				<div class="row justify-content-center mt-5">
				<div class="col-md-12">
					<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
					</div>
				</div>
				</div>
			</div>
			@endif
		    @if(Session::has('msg'))
		      <div class="container">
		        <div class="row justify-content-center mt-5">
		          <div class="col-md-12">
		            <div class="alert alert-info">
		                {{Session::get('msg')}}
		            </div>
		          </div>
		        </div>
		      </div>
		    @endif
			@yield('content')
		    @if(Session::has('debug'))
		      <div class="container">
		        <div class="row justify-content-center mt-5">
		          <div class="col-md-12">
		            <div class="alert alert-info">
		                {{ dd(Session::get('debug')) }}
		            </div>
		          </div>
		        </div>
		      </div>
		    @endif
		</div>
	</main>

	@if(Auth::check())
		@include('layouts/edituserprofile')
	@endif

	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="{{asset('js/select2.min.js')}}"></script>
	<script type="text/javascript">
		$(function(){
			$('#userEditModal').on('shown.bs.modal', function (e) {
				$('.multiselect').select2();
			});
		});
	</script>
	@yield('scripts')
</body>
</html>