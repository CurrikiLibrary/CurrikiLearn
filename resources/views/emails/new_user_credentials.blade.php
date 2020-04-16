<img class="management-brand" src="{{ asset('images/logo.jpg') }}" alt="{{ env('APP_NAME') }} Curriculum Writing Projects" height="100px">
<br>
<p>Welcome to {{ env('APP_NAME') }} Curriculum Writing Projects. A user has been created for you. Please use the following credentials to login.</p>
<br>
<b>Username:</b> {{$username}}
<br>
<b>Password:</b> {{$password}}
<br>
<br>
<a href="{{ url('/') }}">Go to {{ env('APP_NAME') }}</a>