<div class="media profile-block">
	<img class="user-photo" src="{{ strlen(Auth::user()->uniqueavatarfile) == 0 ? asset('images/user_avatar.png') : env('CURRIKI_AVATARS_BASE_URL').Auth::user()->uniqueavatarfile}}" width="103" height="103" alt="user Photo">
	<div class="media-body">
		<span class="user-name">{{ Auth::user()->full_name  }}</span>
		<ul class="list-horizontal">
			<li>Organization: {{ Auth::user()->organization ? Auth::user()->organization : 'N/A' }}</li>
			<li>Language: {{ Auth::user()->language ? Auth::user()->languageName->displayname : 'N/A' }}</li>
			<li>Joined: {{ Auth::user()->registerdate ? Auth::user()->registerdate->format('M d, Y g:i a') : '' }}</li>
			<li>Last Activity: {{ Auth::user()->logins->count() > 0 ? Auth::user()->logins->max('logindate')->format('M d, Y g:i a') : '' }}</li>
		</ul>
	</div>
</div>