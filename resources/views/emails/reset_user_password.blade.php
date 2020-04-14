<img class="management-brand" src="{{ asset('images/' . (env('NASSAU_HUB_ID') == 1 ? 'logo.jpg' : 'NEAF-logo-cmyk.jpg')) }}" alt="{{ env('APP_NAME') }} Curriculum Writing Projects" height="100px">
<br>
<p>Your password has been reset. Please use the following credentials to login.</p>
<br>
<b>Username:</b> {{$username}}
<br>
<b>Password:</b> {{$password}}
<br>
<br>
<a href="{{ url('/') }}">Go to {{ env('APP_NAME') }}</a>