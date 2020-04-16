@extends('layouts.management')

@section('content')
	<div class="row mb-2">
		<div class="col">
			<div class="alert alert-primary" role="alert">
				Manage your groups and memberships.
			</div>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col">
			<form method="get">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Find a group..." aria-describedby="basic-addon2" name="query" value="{{ $query  }}">
					<div class="input-group-append">
						<button type="submit" class="input-group-text" id="basic-addon2">Search</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Group</th>
						<th scope="col">Roles</th>
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody>
				@forelse ($groups as $group)
					<tr>
						<th>{{ $group->name }}</th>
						<td>
							<span class="badge badge-info">{!! Auth::user()->roles($group->id)->get()->implode('display_name', '</span> <span class="badge badge-info">') !!}</span>
						</td>
						<td>
							<a class="btn btn-primary resource-back-btn d-block mb-1" href="{{url('/group_management/'.$group->id)}}">Manage</a>
						</td>
					</tr>
				@empty
					<tr>
						<th colspan="4">No Resource Found.</th>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col">
			<nav aria-label="...">
			
			</nav>
		</div>
	</div>
@endsection

@section('scripts')
	
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