<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ env('APP_NAME') }} Curriculum Writing Projects</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind+Siliguri:400,600,700&display=swap">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style type="text/css">
		/* Floating user side menu */ 

		.user-side-menu {
		  position: fixed;
		  right: 0px;
		  text-align: right;
		  z-index: 1;
		}

		.user-side-menu button {
		  width: 50px;
		  height: 50px;
		  background-color: #607a9b;
		  border: 0px;
		}

		.user-side-menu-container {
		    text-align: center;
		    padding: 5px;
		    right: 50px;
		    position: absolute;
		    width: 200px;
		    border: solid 1px Gainsboro;
			display: none;
			background-color: #ffffff;
		}

		.user-side-menu-container div {
		    text-align: center;
		    padding: 10px;
		    border-bottom: Gainsboro 1px solid;
		}

		.user-side-menu-container div:last-child {
		    border-bottom: 0px;
		}

		.user-side-menu-container div a {
		  color: #607a9b;
		  text-decoration: none;
		}

		.icon {
 			 display: inline-block;
		}
		.icon-user {
		  width: 22px;
		  height: 24px;
		  background-image: url(../images/icon_user.png);
		  background-repeat: no-repeat;
		}

	</style>
	@yield('styles')
</head>
<body>
	<div class="container">
		<div class="row mt-4">
			<div class="col">
				<img class="management-brand" src="{{ asset('images/logo.jpg') }}" alt="{{ env('APP_NAME') }} Curriculum Writing Projects">
			</div>
			<div class="col text-right">
				<a class="btn btn-primary resource-back-btn" href="{{url('/')}}">Back</a>
			</div>
		</div>
		@if (Auth::check())
			<div class="user-side-menu">
				<button class="btn btn-primary no-arrow user-side-menu-button" type="button"><i class="icon icon-user"></i></button>
				<div class="user-side-menu-container">
					<div><a href="{{url('/resource_management')}}">My Account</a></div>
					<div><a href="{{url('/resource_management')}}">Manage Resources</a></div>
					<div><a href="{{url('/group_management')}}">Manage Membership</a></div>
					<div><a href="{{url('/user_management')}}">Manage Users</a></div>
					<div><a href="{{url('/reports')}}">Reports</a></div>
					<div><a href="{{url('/logout_api')}}">Logout</a></div>
				</div>
			</div>
		@else
			<div class="user-side-menu">
				<button class="btn btn-primary no-arrow user-side-menu-button" type="button"><i class="icon icon-user"></i></button>
				<div class="user-side-menu-container">
					<div><a class="user-side-menu-login-button" href="#">Login</a></div>
				</div>
			</div>
		@endif
		<div class="row mt-4">
			<div class="col-3">
				<div class="card">
					<img src="{{ strlen(Auth::user()->uniqueavatarfile) == 0 ? asset('images/user_avatar.png') : env('CURRIKI_AVATARS_BASE_URL').Auth::user()->uniqueavatarfile}}" class="card-img-top" alt="User avatar">
					<div class="card-body">
						<h2>About</h2>
						<p class="card-text">
							{!! Auth::user()->bio !!}
						</p>
					</div>
				</div>
			</div>
			<div class="col-9">
				<div class="row mb-5">
					<div class="col user-details">
						<h2>{{ Auth::user()->full_name }}</h2>
						<div>
							<label>Organization:</label>
							{{ Auth::user()->organization ? Auth::user()->organization : 'N/A' }}
						</div>
						<div>
							<label>Language:</label>
							{{ Auth::user()->language ? Auth::user()->languageName->displayname : 'N/A' }}
						</div>
						<div>
							<label>Joined:</label>
							{{ Auth::user()->registerdate ? Auth::user()->registerdate->format('M d, Y g:i a') : '' }}
						</div>
						<div>
							<label>Last Activity:</label>
							{{ Auth::user()->logins->count() > 0 ? Auth::user()->logins->max('logindate')->format('M d, Y g:i a') : '' }}
						</div>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col">
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link " href="{{url('/resource_management')}}">Resources</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{url('/group_management')}}">Groups</a>
							</li>
						</ul>
					</div>
				</div>
				@yield('content')
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript">
		$(document).ready(function(){	
			$('.user-side-menu-button').on('click', function(){
				$('.user-side-menu-container').css('display', 'inherit');
			});
			$('.user-side-menu-container').on('mouseleave', function(){
				$('.user-side-menu-container').css('display', 'none');
			});
		});
	</script>
	@yield('scripts')
</body>
</html>
