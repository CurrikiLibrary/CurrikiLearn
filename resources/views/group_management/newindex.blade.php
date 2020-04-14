@extends('layouts.management2')

@section('content')
	@include('layouts.profilebanner')

	<ul class="nav nav-tabs bs-mb-4" role="tablist">
		<li class="nav-item">
			<a class="nav-link {{($section == 'resources') ? 'active':''}}" href="{{url('/resource_management')}}">Resources</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{($section == 'groups') ? 'active':''}}" href="#groups-tab">Groups</a>
		</li>
	</ul>
	<div class="tab-content">
	  <div class="tab-pane active" id="groups-tab">
		<h2 class="section-title">Manage Membership</h2>
<!-- 		<div class="row pb-3">
			<div class="col-md-7">
				<form class="form-search-membership" action="#" method="get">
					<div class="input-group input-group-primary mb-3">
						<input type="search" class="form-control text-primary" placeholder="Search for a group">
						<div class="input-group-append">
							<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</form>
			</div>
		</div> -->
		<div class="table-responsive mb-5">
			<table class="table table-default">
				<thead>
					<tr>
						<th>Groups</th>
						<th>Roles</th>
						<th class="text-right">Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($groups as $group)
						<tr>
							<td><strong>{{ $group->name }}</strong></td>
							<td>
									@if(in_array($group->id, $adminGroups))
										<span class="badge badge-info">Administrator</span>
									@else
									<span class="badge badge-info">
									{!! Auth::user()->roles($group->id)->get()->implode('display_name', '</span><span class="badge badge-info">') !!}
									</span>
									@endif
							<td class="text-right">
								<div class="btn-group action-buttons">
									@if(in_array($group->id, $adminGroups))
										<a class="btn btn-edit" href="{{url('/group_management/'.$group->id)}}"><i class="fa fa-pencil"></i></a>
									@endif
								</div>
							</td>
						</tr>

					@empty
					<tr>
						<th colspan="3">No Resource Found.</th>
					</tr>
					@endforelse						
				</tbody>
			</table>
		</div>
	  </div>
	</div>
@endsection

@section('styles')
@endsection
@section('scripts')

@endsection