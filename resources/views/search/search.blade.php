@extends('layouts.public')

@section('content')
    <form class="form-search" action="{{ url('/') }}" method="get">
        <fieldset>
            <div class="form-group position-relative">
                <input class="form-control form-control-search" type="search" placeholder="Search Curriculum Projects" name="query" value="{{$params['query']}}">
                <button type="submit" class="btn btn-search btn-primary"><i class="fa fa-search"></i></button>
            </div>
            <div>

            </div>
            <div class="d-flex justify-content-between filter-row">
				<div class="dropdown">
					<a class="btn btn-filter dropdown-toggle" href="#" role="button" id="subjectsDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Subject</a>
					
					<div class="dropdown-menu" aria-labelledby="subjectsDropdownLink">
                        @foreach($subjects as $subject)
                            <a class="dropdown-item" href="{{ url('/').'/?subject='.$subject->subjectid }}">{{ $subject->displayname }}</a>
                        @endforeach
                    </div>                    
                </div>
                <div class="dropdown">
					<a class="btn btn-filter dropdown-toggle" href="#" role="button" id="levelsDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Level</a>
					
                    <div class="dropdown-menu" aria-labelledby="levelsDropdownLink">
                        @foreach($level_groups as $level)
                            <a class="dropdown-item" href="{{ url('/').'/?level='.$level->id }}">{{ $level->display_name }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="dropdown show">
                    <a class="btn btn-filter dropdown-toggle" href="#" role="button" id="districtDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">District</a>
					
					<div class="dropdown-menu" aria-labelledby="districtDropdownLink">
                        @foreach($groups as $group)
                            <a class="dropdown-item" href="{{ url('/').'/?group='.$group->id }}">{{ $group->name }}</a>
                        @endforeach
                    </div>
                </div>
                <!--
                <div class="dropdown">
                    <a class="btn btn-filter dropdown-toggle" href="#" role="button" id="archivedDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Archived</a>
					
                    <div class="dropdown-menu" aria-labelledby="archivedDropdownLink">
                        <a class="dropdown-item" href="#">Coming soon</a>
                    </div>
                </div>      
                -->                
            </div>
        </fieldset>
    </form>

    <div class="result-container">
        @if(!empty($params['query']))
			<div class="heading-rs-holder">
				<h2 class="heading-rs text-center">SEARCH RESULTS FOR "<span class="text-uppercase font-weight-bold">{{$params['query']}}</span>"</h2>
			</div>
		@endif
		
        @if(!empty($params['subjects']) || !empty($params['levels']) || !empty($params['groups']))
            <div class="filter-control bg-white-block d-flex">
                <div class="filter-control-left">
                    Filter: 
                    @if(!empty($params['subjects']))
                        <br>Subjects: 
                        @foreach($params['subjects'] as $subject)
                            <a class="filter-tag" href="{{ url('/').'/?rsubject='.$subject }}">
                                {{$subjects->where('subjectid', $subject)->first()->displayname}}
                            </a>
                        @endforeach
                    @endif
                    @if(!empty($params['levels']))
                        <br>Levels: 
                        @foreach($params['levels'] as $level)
                            <a class="filter-tag" href="{{ url('/').'/?rlevel='.$level }}">
                                {{$level_groups->where('id', $level)->first()->display_name}}
                            </a>
                        @endforeach
                    @endif
                    @if(!empty($params['groups']))
                        <br>Districts: 
                        @foreach($params['groups'] as $group)
                            <a class="filter-tag" href="{{ url('/').'/?rgroup='.$group }}">
                                {{$groups->where('id', $group)->first()->name}}
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="ml-auto">
                    <a class="filter-tag filter-tag-remove" href="{{ url('/').'?rall=true' }}">Clear All</a>
                </div>
            </div>
        @endif
        @if($results->isEmpty())
            <div class="container">
                <div class="row justify-content-center mt-5">
                  <div class="col-md-12">
                    <div class="alert alert-info">
                        Nothing found.
                    </div>
                  </div>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-end mb-3">
				<div class="d-inline-block">
                    <div class="dropdown">
                        <button class="btn btn-outline dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:white;">
                            Sort by
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{url('/?sort=al_asc')}}">A-Z</a>
                            <a class="dropdown-item" href="{{url('/?sort=al_desc')}}">Z-A</a>
                            <a class="dropdown-item" href="{{url('/?sort=date_asc')}}">Oldest First</a>
                            <a class="dropdown-item" href="{{url('/?sort=date_desc')}}">Newest First</a>
                        </div>
                    </div>
				</div>
			</div>
            @foreach($results as $result)
                <div class="article">
                    <h4 class="article-title">
                        <a href="{{url('/resource/'.$result->resourceid)}}">
                            {{ (isset($result['title'])) ? $result['title'] : 'N/A' }}
                        </a>
                    </h4>
                    <div class="media">
                        <div class="media-body">
                            <div class="meta">
                                <b>Education Levels:</b>
                                    @forelse($result->getEducationLevelGroupings() as $level)
                                        <span>{{$level->display_name}}</span>
                                    @empty
                                        N/A
                                    @endforelse
                                <br>
                                <b>Subject Area:</b>
                                    @forelse($result->subjectAreas as $area)
                                        <span>{{$area->displayname}}</span>
                                    @empty
                                        N/A
                                    @endforelse
                            </div>
                            <p>
                                @if(isset($result['description']))
                                    {{ (strlen($result['description']) > 255) ? substr($result['description'], 0, 252).'...' : $result['description'] }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <a class="btn btn-bs btn-primary align-self-center" href="{{url('/resource/'.$result->resourceid)}}">Go To Resource</a>
                    </div>
                </div>
            @endforeach
            {{ $results->appends([$params])->links('layouts.pagination') }}
        @endif
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>
@endsection