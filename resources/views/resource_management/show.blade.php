@extends('layouts.lean')

@section('content')
<div class="row mt-5 mb-2">
    <div class="col">
        <h2>Resource Management</h2>
    </div>
</div>
<div class="row mb-2">
    <div class="col">
        <div class="alert alert-primary" role="alert">
            View and edit this resource.
        </div>
    </div>
</div>
<div class="card uper">
    <div class="card-header">
        <h3>{{ $resource->title }}</h3>
    </div>
    <div class="card-body">
        <form>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Description: </label>
                <div class="col-sm-10">
                    <span class="col-sm-10 col-form-label">{!! $resource->description !!}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Created: </label>
                <div class="col-sm-10">
                    <span class="col-sm-10 col-form-label">{{ $resource->contributiondate ? $resource->contributiondate->format('M d, Y') : '' }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Modified: </label>
                <div class="col-sm-10">
                    <span class="col-sm-10 col-form-label">{{ $resource->lasteditdate ? $resource->lasteditdate->format('M d, Y') : '' }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Education Levels: </label>
                <div class="col-sm-10">
                    <span class="col-sm-10 col-form-label">{{ $resource->educationLevels->implode('identifier', ', ') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Subject Areas: </label>
                <div class="col-sm-10">
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
                                @endforelse
                            </ul>
                        </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col text-left">
                    <a class="btn btn-primary" href='{{ url()->previous() }}'>Cancel</a>
                </div>
                <div class="col text-right">
                    <a class="btn btn-primary" href='{{url("/resource_management/{$resource->resourceid}/edit")}}'>Edit</a>
                </div>
            </div>
        </form>
    </div>
</div>
<br/><br/>
@endsection

@section('scripts')
@endsection
@section('styles')
    <style type="text/css">
        .card-body label {
            font-weight: bolder;
        }

        .card-body p {
            margin-top:1rem;
        }

        .btn-primary {
            background-color:#607a9b;
            border-color: #607a9b;
        
        }
    </style>
@endsection