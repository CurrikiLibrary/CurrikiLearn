@extends('layouts.management2')

@section('content')
	@include('layouts.profilebanner')

	<ul class="nav nav-tabs bs-mb-4" role="tablist">
		<li class="nav-item">
			<a class="nav-link {{($section == 'resources') ? 'active':''}}" href="#resources-tab">Resources</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{($section == 'groups') ? 'active':''}}" href="{{url('/group_management')}}">Groups</a>
		</li>
	</ul>
	<div class="tab-content">
	  <div class="tab-pane active" id="resources-tab">
		<h2 class="section-title">Manage Resources</h2>
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
		<div class="row pb-3">
			<div class="col-md-7">
				<form class="form-search-resources" action="{{url('/resource_management')}}" method="get">
					<div class="input-group input-group-primary mb-3">
						<input type="search" class="form-control text-primary" placeholder="Search for resources" name="query" value="{{$params['query']}}">
						<div class="input-group-append">
							<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-5">
	            <div class="d-flex justify-content-between filter-row">
					<div class="dropdown">
						<a class="btn btn-filter dropdown-toggle" href="#" role="button" id="subjectsDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Subject</a>
						
						<div class="dropdown-menu" aria-labelledby="subjectsDropdownLink">
	                        @foreach($subjects as $subject)
	                            <a class="dropdown-item" href="{{ url('/resource_management').'/?subject='.$subject->subjectid }}">{{ $subject->displayname }}</a>
	                        @endforeach
	                    </div>                    
	                </div>
	                <div class="dropdown">
						<a class="btn btn-filter dropdown-toggle" href="#" role="button" id="levelsDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Level</a>
						
	                    <div class="dropdown-menu" aria-labelledby="levelsDropdownLink">
	                        @foreach($level_groups as $level)
	                            <a class="dropdown-item" href="{{ url('/resource_management').'/?level='.$level->id }}">{{ $level->display_name }}</a>
	                        @endforeach
	                    </div>
	                </div>
	                <div class="dropdown show">
	                    <a class="btn btn-filter dropdown-toggle" href="#" role="button" id="districtDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">District</a>
						
						<div class="dropdown-menu" aria-labelledby="districtDropdownLink">
	                        @foreach($groups as $group)
	                            <a class="dropdown-item" href="{{ url('/resource_management').'/?group='.$group->id }}">{{ $group->name }}</a>
	                        @endforeach
	                    </div>
	                </div>           
	            </div>
			</div>

		</div>
		<div class="row pb-3">
			<div class="col-12">
		        @if(!empty($params['subjects']) || !empty($params['levels']) || !empty($params['groups']))
		            <div class="filter-control bg-white-block d-flex">
		                <div class="filter-control-left">
		                    Filter: 
		                    @if(!empty($params['subjects']))
		                        <br>Subjects: 
		                        @foreach($params['subjects'] as $subject)
		                            <a class="filter-tag" href="{{ url('/resource_management').'/?rsubject='.$subject }}">
		                                {{$subjects->where('subjectid', $subject)->first()->displayname}}
		                            </a>
		                        @endforeach
		                    @endif
		                    @if(!empty($params['levels']))
		                        <br>Levels: 
		                        @foreach($params['levels'] as $level)
		                            <a class="filter-tag" href="{{ url('/resource_management').'/?rlevel='.$level }}">
		                                {{$level_groups->where('id', $level)->first()->display_name}}
		                            </a>
		                        @endforeach
		                    @endif
		                    @if(!empty($params['groups']))
		                        <br>Districts: 
		                        @foreach($params['groups'] as $group)
		                            <a class="filter-tag" href="{{ url('/resource_management').'/?rgroup='.$group }}">
		                                {{$groups->where('id', $group)->first()->name}}
		                            </a>
		                        @endforeach
		                    @endif
		                </div>
		                <div class="ml-auto">
		                    <a class="filter-tag filter-tag-remove" href="{{ url('/resource_management').'?rall=true' }}">Clear All</a>
		                </div>
		            </div>
		        @endif
	    	</div>
		</div>
		<div class="row pb-3">
			<div class="col">
				<div class="dropdown">
					<button class="btn btn-outline dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:white;">
						Sort by
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="{{url('/resource_management?sort=al_asc')}}">A-Z</a>
						<a class="dropdown-item" href="{{url('/resource_management?sort=al_desc')}}">Z-A</a>
						<a class="dropdown-item" href="{{url('/resource_management?sort=date_asc')}}">Oldest First</a>
						<a class="dropdown-item" href="{{url('/resource_management?sort=date_desc')}}">Newest First</a>
					</div>
				</div>
			</div>
			<div class="col text-md-right">
				<a class="btn btn-outline" href="{{url("/resource_management/create")}}"><i class="fa fa-file-archive-o"></i> Create New Resource</a>
			</div>
		</div>
		<div class="table-responsive mb-5">
			<table class="table table-default">
				<thead>
					<tr>
						<th>Title</th>
						<th>Education Levels</th>
						<th>Subject Area</th>
						<th>Description</th>
						<th class="text-right">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($resources as $resource)
						<tr>
							<td><strong>{{ $resource->title }}</strong></td>
							<td>
                                @forelse($resource->getEducationLevelGroupings() as $level)
                                    <span>{{$level->display_name}}</span>
                                @empty
                                    N/A
                                @endforelse
							<td>
								<ul class="list-unstyled">
									@forelse ($resource->subjectAreasBySubject() as $key => $subjectAreas)
										<li>
											{{ $key }}
											<ul class="list-unstyled">
											@forelse ($subjectAreas as $subjectArea)
												<li class="list-inline-item">
													<span class="badge badge-primary">{{ $subjectArea->displayname }}</span>
												</li>
											@empty
												N/A
											@endforelse
											</ul>
										</li>
									@empty
										N/A
									@endforelse
								</ul>
							</td>
							<td>
								<div class="desc">
									@if($resource->type == 'collection')
										<span class="badge badge-info">Collection</span><br>
									@endif
									{{ (strlen($resource->description) > 255) ? substr($resource->description, 0, 252).'...' : $resource->description }}
								</div>
							</td>
							<td class="text-right">
								<div class="btn-group action-buttons">
									@if($resource->visibility(1)->where('visibility_id', 4)->first())
										<br>
										<div class="alert alert-info" role="alert">
										  	Archived
										</div>
										<a class="btn btn-view" href="{{url('/resource/'.$resource->resourceid)}}" title="View Resource"><i class="fa fa-folder-open"></i></a>
										<a class="btn btn-delete delete-resource-btn" rscid="{{$resource->resourceid}}" href="#" title="Delete Resource"><i class="fa fa-trash"></i></a>
									@else
										<a class="btn btn-view" href="{{url('/resource/'.$resource->resourceid)}}" title="View Resource"><i class="fa fa-folder-open"></i></a>
										<a class="btn btn-edit" href="{{url("/resource_management/{$resource->resourceid}/edit")}}" title="Edit Resource"><i class="fa fa-pencil"></i></a>
										<a class="btn btn-delete delete-resource-btn" rscid="{{$resource->resourceid}}" href="#" title="Delete Resource"><i class="fa fa-trash"></i></a>
									@endif
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		{{ $result->appends([])->links('layouts.pagination') }}
	  </div>
	</div>

	<div class="modal" id="resource-delete-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Delete Resource</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this resource?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<form id="deleteResourceForm" method="POST" action="">
						@method('delete')
						@csrf
						<button type="submit" class="btn btn-danger">Delete</button>
					</form>	
				</div>
			</div>
		</div>
	</div>
@endsection

@section('styles')
@endsection
@section('scripts')
	<script type="text/javascript">
		var baseDeleteUrl = "{{ url('/resource_management') }}";
		$(function(){
			$('.delete-resource-btn').on('click', (e) => {
				var id = $(e.currentTarget).attr('rscid');
				$('#deleteResourceForm').attr('action', baseDeleteUrl + '/' + id);
				$('#resource-delete-modal').modal();
			});
		});	
	</script>
@endsection