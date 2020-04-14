@extends('layouts.lean')

@section('content')
	<div class="row mt-5">
		<div class="col">
			<h2>Hub Reports</h2>	

		</div>
	</div>
	<div class="row mt-2">
		<div class="col">
			<div class="alert alert-primary" role="alert">
				Explore live metrics from the hub.
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-3">
			<div class="card">
				<div class="card-body">
					<h2>Monthly Views:</h2>
					<p class="text-right h3">{{number_format($monthlyViews)}}</p>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h2>Monthly Creations:</h2>
					<p class="text-right h3">{{number_format($monthlyCreations)}}</p>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h2>Total Resources:</h2>
					<p class="text-right h3">{{number_format($totalResources)}}</p>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h2>Total Users:</h2>
					<p class="text-right h3">{{number_format($totalUsers)}}</p>
				</div>
			</div>
		</div>
		<div class="col-9">
			<div class="row">
				<div class="col">
					<div class="row">
						<div class="col">
							<ul class="nav nav-pills mb-3 float-right" id="pills-tab" role="tablist">
							  <li class="nav-item">
							    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Views</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Resources</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Users</a>
							  </li>
							</ul>
						</div>
					</div>
					<div class="tab-content" id="pills-tabContent">
					  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						<div class="row">
							<div class="col">
								<h3>Resource Views</h3>
								<canvas id="views_general_line" width="200" height="100"></canvas>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<h3>Views by District</h3>
								<canvas id="views_district_pie" width="100" height="100"></canvas>
							</div>
							<div class="col-6">
								<h3>Views by Educational Level</h3>
								<canvas id="views_level_pie" width="100" height="100"></canvas>
							</div>
						</div>
					  </div>
					  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
						<div class="row">
							<div class="col">
								<h3>Resource Creation</h3>
								<canvas id="creations_general_line" width="200" height="100"></canvas>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<h3>Resources by District</h3>
								<canvas id="resources_district_pie" width="100" height="100"></canvas>
							</div>
							<div class="col-6">
								<h3>Resources by Educational Level</h3>
								<canvas id="resources_level_pie" width="100" height="100"></canvas>
							</div>
						</div>
					  </div>
					  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
						<div class="row">
							<div class="col">
								<div class="row">
									<div class="col">
										<h3>Users by District</h3>
										<canvas id="users_district_pie" width="50" height="50"></canvas>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<h3>Users by Educational Level</h3>
										<canvas id="users_level_pie" width="50" height="50"></canvas>
									</div>
								</div>
							</div>
							<div class="col">
								<h3>Top Contributors</h3>
								<table class="table table-striped">
									<thead>
										<tr>
											<th scope="col">Name</th>
											<th scope="col">Email</th>
											<th scope="col">Resources</th>
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
													<td>{{ $user->display_name}}</td>
													<td>{{ $user->user_email}}</td>
													<td>
														{{rand(1,50)}}
													</td>
												</tr>
											@endforeach
										@endif

									</tbody>
								</table>
							</div>
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>




@endsection
@section('styles')
@endsection
@section('scripts')
<input type="hidden" value="{{$monthlyViewsByDistrict}}" id="monthlyViewsByDistrictData">
<input type="hidden" value="{{$monthlyViewsByLevel}}" id="monthlyViewsByLevelData">
<input type="hidden" value="{{$viewsLineData}}" id="viewsLineData">

<input type="hidden" value="{{$creationLineData}}" id="creationLineData">

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes@latest/dist/chartjs-plugin-colorschemes.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('.sectionTabButton').on('clicked', sectionButtonClicked);
		new Chart($('#views_district_pie'), {
		    type: 'pie',
		    data: JSON.parse($('#monthlyViewsByDistrictData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		new Chart($('#views_level_pie'), {
		    type: 'pie',
		    data: JSON.parse($('#monthlyViewsByLevelData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		new Chart($('#views_general_line'), {
		    type: 'line',
		    data: JSON.parse($('#viewsLineData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		new Chart($('#creations_general_line'), {
		    type: 'bar',
		    data: JSON.parse($('#creationLineData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		new Chart($('#resources_district_pie'), {
		    type: 'pie',
		    data: JSON.parse($('#monthlyViewsByDistrictData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		new Chart($('#resources_level_pie'), {
		    type: 'pie',
		    data: JSON.parse($('#monthlyViewsByLevelData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		//

		new Chart($('#users_district_pie'), {
		    type: 'pie',
		    data: JSON.parse($('#monthlyViewsByDistrictData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
		new Chart($('#users_level_pie'), {
		    type: 'pie',
		    data: JSON.parse($('#monthlyViewsByLevelData').val()),
			options: {
				plugins: {
			        colorschemes: {
			            scheme: 'brewer.Paired12'
			        }
			    }
			}
		});
	});
	
	function sectionButtonClicked(){
		// if id init graphs for section
	}

</script>
@endsection