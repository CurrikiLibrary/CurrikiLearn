@extends('layouts.lean')

@section('content')
	<div class="row">
		<div class="col"><h2 class="section-title">Manage Membership</h2></div>
		<div class="col text-right"><a href="{{url('/group_management')}}" title="Back to group management" class="btn btn-primary">Back</a></div>
	</div>
	
	<div class="media profile-block mb-5">
		<img class="user-photo" src="{{  asset('images/user_avatar.png')}}" width="125" height="125" alt="User avatar">
		<div class="media-body user-details text-primary">
			<h3 class="section-title fs-26 mb-0">{{$group->name}}</h3>
			<ul class="list-horizontal mb-0">
				<li><label>Parent Group:</label> 
					@if(empty($group->parent_id))
						-NA-
					@else
						{{$group->parent->name}}
					@endif
				</li>
			</ul>
		</div>
	</div>
	<h3 class="section-title fs-26">Manage this group's users and roles.</h3>
	<div class="row pb-3">
		<div class="col-md-7">
			<form method="get">
				<div class="input-group input-group-primary mb-3">
					<input type="text" class="form-control text-primary" placeholder="Find a user..." aria-describedby="basic-addon2" name="query" value="{{$query}}">
					<div class="input-group-append">
						<button type="submit" class="btn btn-primary" id="basic-addon2"><i class="fa fa-search"></i> Search</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-5 text-md-right">
			<a class="btn btn-outline add-user-btn" data-toggle="modal" href="#userAddModal"><i class="fa fa-file-archive-o"></i> Add User</a>
		</div>
	</div>
	<div class="table-responsive mb-5">
		<table class="table table-default">
			<thead>
				<tr>
					<th scope="col">Name</th>
					<th scope="col">Email</th>
					<th scope="col">Roles</th>
					<th scope="col" class="text-right">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if(count($users) < 1)
					<tr>
						<th colspan="4">No users found.</th>
					</tr>
				@else
					@foreach($users as $user)
						<tr>
							<td><strong>{{ $user->display_name}}</strong></td>
							<td>{{ $user->user_email}}</td>
							<td>
								<span class="badge badge-info">
									{!! $user->roles($group->id)->get()->implode('display_name', '</span> <span class="badge badge-info">') !!}
								</span>
							</td>
							<td class="text-right">
								<div class="btn-group action-buttons align-items-center">
									<a class="btn btn-edit" href="#" title="Edit Member" user_id="{{$user->ID}}"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-delete-o group-delete-button" href="#" user_id="{{$user->ID}}"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>
					@endforeach
				@endif

			</tbody>
		</table>
	</div>
	{{ $users->appends([$query])->links('layouts.pagination') }}
</div>

<!-- Add user modal --->
<form action="{{url('/group_management/'.$group->id.'/adduser')}}" method="POST">
	{{ csrf_field() }}
	<div class="modal modal-primary fade" id="userAddModal" tabindex="-1" role="dialog" aria-labelledby="userAddModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fs-26 text-primary" id="userAddModalLabel">Add New Member</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="fs-22 text-primary font-weight-semibold mb-3">Search for a user and add it to this group.</div>
					<div class="input-group input-group-primary mb-3">
						<input id="user-search-input" type="text" class="form-control text-primary" placeholder="Find a user..." aria-describedby="modal-user-search-field">
						<input type="hidden" id="user-add-user-id" name="userid" value="">
						<div class="input-group-append">
							<a class="btn btn-primary" id="modal-user-search-field" href="#"><i class="fa fa-search"></i> Search</a>
						</div>
					</div>
					<div class="text-primary mb-5" id="user-add-details-placeholder">
						No user selected. Use the search field above to find users and view their details before adding them to the group.
					</div>
					<div class="mb-5" id="user-add-details">
						<div class="media bs-mb-2">
							<img class="user-photo align-self-center" id="user-add-avatar" src="{{ asset('images/user_avatar.png') }}" width="103" height="103" alt="User avatar">
							<div class="media-body align-self-center">
								<h2 class="section-title fs-26 mb-0" id="user-add-details-name">N/A</h2>
								<ul class="list-unstyled">
									<li class="text-primary">
										<label>E-Mail:</label>
										<span id="user-add-details-email">N/A</span>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="fs-22 text-primary font-weight-semibold mb-3 pt-4">Select the role that the user will perform in this group.</div>
						<select id="user-add-details-role" class="form-control form-control-square-lg text-primary" name="role">
							<option value="none">Select Role</option>
							@foreach($roles as $role)
								<option value="{{$role->id}}">{{$role->display_name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer buttonpane">
					<button type="button" class="btn btn-mute mr-0" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" id="user-add-modal-save">Add User</button>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Edit user modal --->
<form action="{{url('/group_management/'.$group->id.'/edituser')}}" method="POST">
	{{ csrf_field() }}
	<input type="hidden" name="user_id" id="user-edit-user-id" value="">
	<div class="modal modal-primary fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="userEditModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fs-26 text-primary" id="userEditModalLabel">Edit Member</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="mb-5">
						<div class="media bs-mb-2">
							<img class="user-photo align-self-center" id="user-edit-avatar" src="{{ asset('images/user_avatar.png') }}" width="103" height="103" alt="User avatar">
							<div class="media-body align-self-center">
								<h2 class="section-title fs-26 mb-0" id="user-edit-details-name">N/A</h2>
								<ul class="list-unstyled">
									<li class="text-primary">
										<label>E-Mail:</label>
										<span id="user-edit-details-email">N/A</span>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="fs-22 text-primary font-weight-semibold mb-3 pt-4">Select the role that the user will perform in this group.</div>
						<select id="user-add-details-role" class="form-control form-control-square-lg text-primary" name="role">
							<option value="none">Select Role</option>
							@foreach($roles as $role)
								<option value="{{$role->id}}">{{$role->display_name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer buttonpane">
					<button type="button" class="btn btn-mute mr-0" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>

	<div class="modal" id="user-remove-modal" tabindex="-1" role="dialog">
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
					<form id="deleteResourceForm" method="POST" action="{{url('/group_management/'.$group->id.'/removeuser')}}">
						@method('delete')
						@csrf
						<input type="hidden" value="" name="user_id" id="userDeleteId">
						<input type="hidden" value="{{$group->id}}" name="group_id" id="groupDeleteId">
						<button type="submit" class="btn btn-danger">Delete</button>
					</form>	
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var avatarBaseUrl = "{{env('CURRIKI_AVATARS_BASE_URL')}}";
		var placeholderAvatarUrl = "{{ asset('images/user_avatar.png') }}";

		$(function(){
			$('.add-user-btn').on('click', addUser);
			$('.btn-edit').on('click', editMember);
			$( "#user-search-input" ).autocomplete({
				source: "{{url('/group_management/'.$group->id.'/user')}}",
				minLength: 2,
				select: userSelected
			});
			$('#user-add-details-role').on('change', () => {
				if($('#user-add-details-role').val() == 'none') {
					$('#user-add-modal-save').fadeOut();
					$('.modal-footer .btn-mute').addClass('mr-0');
				}
				else {
					$('#user-add-modal-save').fadeIn();
					$('.modal-footer .btn-mute').removeClass('mr-0');
				}
			});
			$('.modal-footer .btn-mute, .modal-header button').on('click', resetUserAddWidget);

			$('.group-delete-button').on('click', (e) => {
				$('#userDeleteId').val($(e.currentTarget).attr('user_id'));
				$('#user-remove-modal').modal();
			});
		});

		function addUser(){
			$('#userAddModal').modal('show');
		}

		function resetUserAddWidget(){
			$('#user-add-user-id').val('');
			$('#user-search-input').val('');			
			$('#user-add-details-role').val('none');
			$('#user-add-details-name').html('N/A');
			$('#user-add-details-email').html('N/A');
			$('#user-add-details-placeholder').css('display', '');
			$('#user-add-details').css('display', 'none');
			$('#user-add-avatar').attr('src', placeholderAvatarUrl);
		}

		function userSelected(event, ui){
			$('#user-add-user-id').val(ui.item.user_id);
			$('#user-add-details-name').html(ui.item.label);
			$('#user-add-details-email').html(ui.item.email);
			
			$('#user-add-details-placeholder').fadeOut(() => {
				$('#user-add-details').fadeIn();
			});
			$('#user-add-avatar').fadeOut(() => {
				if(ui.item.avatar_filename != null)
					$('#user-add-avatar').attr('src', avatarBaseUrl+ui.item.avatar_filename);
				else
					$('#user-add-avatar').attr('src', placeholderAvatarUrl);
				$('#user-add-avatar').fadeIn();
			});
		}

		function editMember(){
			var id = $(this).attr('user_id');
			$.get("{{url('/group_management/'.$group->id.'/user')}}?user_id="+id, (response) => {
				var userData = JSON.parse(response);
				$('#user-edit-user-id').val(userData.id);
				$('#user-edit-details-name').html(userData.name);
				$('#user-edit-details-email').html(userData.email);
				if(userData.avatar_filename != null)
					$('#user-add-avatar').attr('src', avatarBaseUrl+userData.avatar_filename);
				else
					$('#user-add-avatar').attr('src', placeholderAvatarUrl);
				$('#userEditModal').modal('show');
			});
		}



	</script>
@endsection
@section('styles')
	<style type="text/css">
		.management-brand {
 			height:120px;
		}
	 	.resource-back-btn {
	 		background-color:#607a9b;
	 		border-color: #607a9b;
	 	}
 		.ui-autocomplete {
 			z-index: 9999 !important;
 		}
 		#user-add-details{
 			display:none;
 		}
 		#user-add-modal-save {
 			display:none;
 		}
	</style>
	
@endsection