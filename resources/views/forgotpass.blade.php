<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ env('APP_NAME') }} Curriculum Writing Projects</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind+Siliguri:400,600,700&display=swap">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
	@yield('styles')
</head>
<body>
	<nav class="navbar navbar-primary navbar-expand-sm">
		<div class="container">
			<a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('images/'. (env('NASSAU_HUB_ID') == 1 ? 'logo_boces.jpg' : 'NEAF-logo-cmyk.jpg'))}}" width="172" height="66" alt="{{ env('APP_NAME') }} Curriculum Writing Projects"></a>
		</div>
	</nav>
	<div class="container py-5">
		<div class="row">
			<div class="col text-center">
				<h2 class="section-title mb-0">Reset Password</h2>
			</div>
		</div>
		<form class="" action="{{url('/doresetpass')}}" method="post">
			{{ csrf_field() }}
			<div class="row">
				<div class="col card">
					<div class="row mt-5">
						<div class="col text-center">
							<div class="alert alert-primary" role="alert">
								To reset your password, please enter your e-mail address below and new credentials will be sent to you.
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col text-center">
					        <label for="group">Email:</label>
			            	<input type="text" class="form-control" name="email">
						</div>
					</div>
					<div class="row mb-5">
						<div class="col text-center">
							<button class="btn btn-primary align-middle mt-2" type="submit" id="basic-addon2"></i>Submit</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>