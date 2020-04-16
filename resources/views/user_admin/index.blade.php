@extends('layouts.lean')

@section('content')
	
	<h2 class="section-title mb-0">User Management</h2>
	<p class="text-primary">Add and remove users from the Hub.</p>
	<div class="row pb-3">
		<div class="col-md-7">
			<form class="form-user-search" method="get">
				<div class="input-group input-group-primary mb-3">
					<input type="search" class="form-control text-primary" placeholder="Search for a user..." aria-describedby="basic-addon2" name="query" value="{{$query}}">
					<div class="input-group-append">
						<button class="btn btn-primary" type="submit" id="basic-addon2"><i class="fa fa-search"></i> Search</button>
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
							<td class="text-right">
								<div class="btn-group action-buttons align-items-center">
									<a class="btn btn-outline btn-extend" href="{{url('/user_management/groups/'.$user->ID)}}">Groups</a>
									<br>
									<a class="btn btn-outline btn-extend" href="{{url('/user_management/reset/'.$user->ID)}}">Send Reset Password E-mail</a>
									<br>
									<a class="btn btn-delete-o user-delete-button" href="{{url('/user_management/remove/'.$user->ID)}}"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>
					@endforeach
				@endif

			</tbody>
		</table>
	</div>
	<nav class="pagination-block mb-5" aria-label="Page navigation">
		{{ $users->appends(['query'=>$query])->links() }}
	</nav>
</div>


<!-- Add user modal --->
<form action="{{url('/user_management/adduser')}}" method="POST">
	{{ csrf_field() }}
	<div class="modal modal-primary fade" id="userAddModal" tabindex="-1" role="dialog" aria-labelledby="userAddModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-with-subtitle text-primary">
						<h5 class="modal-title fs-26" id="userAddModalLabel">Add New User</h5>
						<div class="modal-desc">Add a new user to the hub.</div>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row mb-2">
						<div class="col-md-7">
							<h3 class="fs-22 font-weight-semibold mb-4">Login Credentials</h3>
							<div class="alert alert-danger" style="display:none;" id="userAddModalAlert" role="alert"><ul></ul></div>
							<div class="form-group mb-4">
								<label class="sr-only" for="username">Username:</label>
								<input type="text" placeholder="Username" id="userModalUsernameField" name="username" class="form-control text-primary">
							</div>
							<div class="form-group mb-5">
								<label class="sr-only" for="email">E-mail:</label>
								<input type="text" placeholder="Email" id="userModalEmailField" name="email" class="form-control text-primary">
							</div>
							<h3 class="fs-22 font-weight-semibold mb-3">User Details</h3>
							<div class="form-group mb-4">
								<label class="sr-only" for="firstname">First Name:</label>
								<input type="text" placeholder="First Name" name="firstname" class="form-control text-primary">
							</div>
							<div class="form-group mb-4">
								<label class="sr-only" for="lastname">Last Name:</label>
								<input type="text" placeholder="Last Name" name="lastname" class="form-control text-primary">
							</div>							
							<div class="form-group">
								<label class="label-primary">Gender:</label>
								<div class="custom-control custom-radio custom-control-inline primary-radio mr-5">
									<input class="custom-control-input" type="radio" name="gender" id="genderMale" value="m" checked>
									<label class="custom-control-label" for="genderMale">Male</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline primary-radio">
									<input class="custom-control-input" type="radio" name="gender" id="genderFemale" value="f">
									<label class="custom-control-label" for="genderFemale">Female</label>
								</div>
							</div>

	                        <div class="alert alert-primary mt-2" role="alert">
	                            What are the user's prefered areas and education levels?
	                        </div>

	                        <label for="levels">Education Levels:</label>
	                        <select class="multiselect form-control" name="levels[]" multiple="multiple">
	                            <option>Select Level</option>
	                            @foreach ($levels as $level)
	                            <option value="{{ $level->id }}">
	                                {{ $level->display_name }}
	                            </option>
	                            @endforeach
	                        </select>

	                        <label for="subjects">Subject Area:</label>
	                        <select class="multiselect form-control" name="subjects[]" multiple="multiple">
	                            <option>Select A Subject Area</option>
	                            @foreach ($subjects as $key => $value)
	                            <option value="{{ $key }}">
	                                {{ $value }}
	                            </option>
	                            @endforeach
	                        </select>

	                        <div class="alert alert-primary mt-2" role="alert">
	                            What group should this user belong to?
	                        </div>

	                        <label for="group">Group:</label>
	                        <select class="form-control" name="group">
	                            <option>Select Group</option>
	                            @foreach ($groups as $group)
	                            <option value="{{ $group->id }}">
	                                {{ $group->name }}
	                            </option>
	                            @endforeach
	                        </select>

	                        <div class="alert alert-primary mt-2" role="alert">
	                            What role should this user perform in the group?
	                        </div>

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
					</div>
				</div>
				<div class="modal-footer buttonpane">
					<button type="button" class="btn btn-mute" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" id="user-add-modal-save">Add User</button>
				</div>
			</div>
		</div>
	</div>
</form>


<!-- Modal -->
<div class="modal fade" id="spinnerModalCenter" tabindex="-1" role="dialog" aria-labelledby="spinnerModalCenter" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="loader"></div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#userAddModal').on('shown.bs.modal', function (e) {
				$('.multiselect').select2();
			});

			$('#user-add-modal-save').on('click', (e) => {
				$('#userAddModal').modal('hide');
				$('#spinnerModalCenter').modal('show');
			});

			$('.user-delete-button').on('click', (e) => {
				var r = confirm('Are you sure you want to delete this user? All resources belonging to this user will be transfered to you.');
				if(r === false)
					e.preventDefault();
			});

			$('#userModalUsernameField').on('change', checkUserCreds);
			$('#userModalEmailField').on('change', checkUserCreds);
		});

		function checkUserCreds(e) {
			$.get(
				'{{ url("/user_management/fetchuser") }}', 
				{ username: $('#userModalUsernameField').val(), email: $('#userModalEmailField').val() },
				(response) => {
					$('#userAddModalAlert').hide();
					$('#userAddModalAlert ul').empty();
					
					if(response.length == 0)
						return;

					if(response.in_hub === true){
						$('#userAddModalAlert ul').append('<li>This user is already a member of {{ env("APP_NAME") }}. Username: '+response.username+' E-mail: '+response.email+'</li>');
						$('#userAddModalAlert').show();
						return;
					}

					if($('#userModalEmailField').val() == response.email && $('#userModalUsernameField').val() == response.username){
						$('#userAddModal input[name="firstname"]').val(response.firstname);
						$('#userAddModal input[name="lastname"]').val(response.lastname);
						$('#userAddModalAlert').hide();
					} else {
						if($('#userModalUsernameField').val() == response.username){
							$('#userAddModalAlert ul').append('<li>Username already exists in Curriki with the following e-mail: '+response.email+'</li>');
							$('#userAddModalAlert').show();
						}

						if($('#userModalEmailField').val() == response.email){
							$('#userAddModalAlert ul').append('<li>E-mail already exists in Curriki with the following username: '+response.username+'</li>');
							$('#userAddModalAlert').show();
						}

						$('#userAddModalAlert ul').append('<li>To add an existing Curriki user to {{ env("APP_NAME") }}, the username and e-mail must match the existing account</li>');
					}
				},
				'json'
			);
		}
	</script>
@endsection
@section('styles')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
@endsection