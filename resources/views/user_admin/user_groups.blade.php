@extends('layouts.management2')

@section('content')

	<div class="row">
		<div class="col"><h2 class="section-title">User Groups</h2></div>
		<div class="col text-right"><a href="{{url('/user_management')}}" title="Back to group management" class="btn btn-primary">Back</a></div>
	</div>

	<div class="media profile-block">
		<img class="user-photo" src="{{ strlen($user->user->uniqueavatarfile) == 0 ? asset('images/user_avatar.png') : env('CURRIKI_AVATARS_BASE_URL').$user->user->uniqueavatarfile}}" width="103" height="103" alt="user Photo">
		<div class="media-body">
			<span class="user-name">{{ $user->user->full_name  }}</span>
			<ul class="list-horizontal">
				<li>Organization: {{ $user->user->organization ? $user->user->organization : 'N/A' }}</li>
				<li>Language: {{ $user->language ? $user->user->languageName->displayname : 'N/A' }}</li>
				<li>Joined: {{ $user->user->registerdate ? $user->user->registerdate->format('M d, Y g:i a') : '' }}</li>
			</ul>
		</div>
	</div>

	<div class="row pb-2">
		<div class="col">
			@if (session('success'))
				<div class="alert alert-success">
					{{session('success')}}
				</div>
			@endif
			@if (session('danger'))
				<div class="alert alert-danger">
					{{session('danger')}}
				</div>
			@endif
		</div>
	</div>
<form class="" action="{{url('/user_management/groups/'.$user->ID.'/add_group')}}" method="post">
	{{ csrf_field() }}
	<div class="row pb-3 media profile-block">
		<div class="col">
            <label for="group">Group:</label>
            <select class="form-control" name="group">
                <option>Select Group</option>
                @foreach ($groups as $group)
                <option value="{{ $group->id }}">
                    {{ $group->name }}
                </option>
                @endforeach
            </select>
		</div>
		<div class="col">
	        <label for="group">Role:</label>
	        <select class="form-control" name="role">
	            <option>Select Role</option>
	            @foreach ($roles as $role)
	            <option value="{{ $role->id }}">
	                {{ $role->display_name }}
	            </option>
	            @endforeach
	        </select>
		</div>
		<div class="col text-center">
			<button class="btn btn-primary align-middle mt-2" type="submit" id="basic-addon2"><i class="fa fa-plus"></i> Add Group</button>
		</div>
	</div>
</form>
	<div class="table-responsive mb-5">
		<table class="table table-default">
			<thead>
				<tr>
					<th>Group</th>
					<th>Role</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($user->groups as $group)
					<tr>
						<td>{{$group->name}}</td>
						<td>
							<span class="badge badge-info">
							{!! $user->roles($group->id)->get()->implode('display_name', '</span><span class="badge badge-info">') !!}
							</span>
						</td>
						<td class="text-right">
							<a class="btn btn-delete-o group-delete-button" href="#" groupid="{{$group->id}}"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="modal" id="ruser-remove-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Remove User</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to remove this user from the group?</p>

					<p>If the user doesn't have access to a group, it won't be possible for them to acces the system.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<form id="deleteResourceForm" method="POST" action="{{url('/user_management/removegroups')}}">
						@method('delete')
						@csrf
						<input type="hidden" value="{{$user->ID}}" name="user_id" id="userDeleteId">
						<input type="hidden" value="" name="group_id" id="groupDeleteId">
						<button type="submit" class="btn btn-danger">Delete</button>
					</form>	
				</div>
			</div>
		</div>
	</div>

@endsection

@section('styles')
<style type="text/css">
	.group-delete-button {
		background-color: #fff;
		border-color: #fd4a4a;
		color: #fd4a4a;
		border-radius: 1.5rem !important;
	}

</style>
@endsection
@section('scripts')
	<script type="text/javascript">
		$('.group-delete-button').on('click', (e) => {
			$('#groupDeleteId').val($(e.currentTarget).attr('groupid'));
			$('#ruser-remove-modal').modal();
		});
	</script>
@endsection