@extends('layouts.management')

@section('content')

	<div class="row mb-2">
		<div class="col">
			<div class="alert alert-primary" role="alert">
				Browse and work on your resources.
			</div>
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
	<div class="row mb-5">
		<div class="col">
			<form method="get">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Find a resource..." aria-describedby="basic-addon2" name="query" value="{{ $query  }}">
					<div class="input-group-append">
						<button type="submit" class="input-group-text" id="basic-addon2">Search</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col text-right">
			<a class="btn btn-primary resource-back-btn mb-1" href='{{url("/resource_management/create")}}'>Create Resource</a>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Title</th>
						<th scope="col">Education Levels</th>
						<th scope="col">Subject Area</th>
						<th scope="col">Description</th>
						<th scope="col">Dates</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse ($resources as $resource)
					<tr>
						<th>{{ $resource->title }}</th>
						<td>{{ $resource->educationLevels->implode('identifier', ', ') }}</td>
						<td>
							<ul>
							@forelse ($resource->subjectAreasBySubject() as $key => $subjectAreas)
								<li>
									{{ $key }}
									<ul>
									@forelse ($subjectAreas as $subjectArea)
										<li>
											{{ $subjectArea->displayname }}
										</li>
									@empty
									@endforelse
									</ul>
								</li>
							@empty
							@endforelse
							</ul>
						</td>
						<td>
							{{ $resource->description }}
						</td>
						<td>
							<label>Created</label>
							{{ $resource->contributiondate ? $resource->contributiondate->format('M d, Y') : '' }}
							<label>Modified</label>
							{{ $resource->lasteditdate ? $resource->lasteditdate->format('M d, Y') : '' }}
						</td>
						<td>
							<a class="btn btn-primary resource-back-btn d-block mb-1" href='{{url("/resource_management/{$resource->resourceid}")}}'>View</a>
							<a class="btn btn-primary resource-back-btn d-block mb-1" href='{{url("/resource_management/{$resource->resourceid}/edit")}}'>Edit</a>
							<a class="btn btn-primary resource-back-btn d-block del-res-btn" data-resourceid="{{ $resource->resourceid }}" href="#">Delete</a>
						</td>
					</tr>
				@empty
					<tr>
						<th colspan="6">No Resource Found.</th>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col">
			<nav aria-label="...">
			{{ $resources->links() }}
			</nav>
		</div>
	</div>

	<div id="myModal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="delete-form" method="POST">
					@csrf
					@method('DELETE')
					<div class="modal-header">
						<h5 class="modal-title">Alert</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Are you sure to delete this resource ?</p>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
		var modal 	= $('#myModal'),
			btn 	= $('.del-res-btn'),
			span 	= $('.close')[0];

		$(btn).on('click', function(event){
			var resourceId = $(event.target).data('resourceid');
			$('#delete-form').attr('action', "{{url('/resource_management')}}/"+resourceId);
			$("#delete-form").val(resourceId);
			modal.show();
		});

		$(span).on('click', function(){
			modal.hide();
		});

		$('#myModal .btn-secondary').on('click', function(){
			modal.hide();
		});

		$(document).on('click', function(event){
			if ($(event.target).is(modal)) {
				modal.hide();
			}
		});
	});
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
	 	.user-details div {
	 		display: inline;
	 		padding: 1em;
	 	}
	 	.user-details label {
			background-color: LightSteelBlue;
			padding: 4px;
			border-radius: 5px;
	 	}
	</style>
	
@endsection