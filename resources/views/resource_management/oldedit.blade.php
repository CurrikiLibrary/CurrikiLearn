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
            Edit this resource.
        </div>
    </div>
</div>
<div class="card uper">
    <div class="card-header">
        <h3>Edit Resource</h3> 
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br />
        @endif
        <form method="post" action='{{url("/resource_management/{$resource->resourceid}")}}'>
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">Title:</label>
                <input type="text" class="form-control" name="title" value="{{ $resource->title }}" />
            </div>
            <div class="form-group">
                <label for="price">Description:</label>
                <textarea rows="10" cols="50" class="form-control" name="description">{{ $resource->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Education Levels:</label>
                <select class="js-example-basic-multiple" name="levelids[]" multiple="multiple">
                    <option>Select Level</option>
                    @foreach ($levels as $key => $value)
                    <option value="{{ $key }}" {{ ( in_array($key, $selectedLevels)) ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Subject Areas:</label>
                <ul>
                    @forelse ($subjects as $subject)
                    <li>
                        <input type="checkbox" value="{{ $subject->subject }}" class="subject-selector" {{ ( in_array($subject->subjectid, $selectedSubjects->items)) ? 'checked' : '' }}> {{ $subject->displayname }}
                        <ul class="{{ $subject->subject }} subject-areas-selector">
                            @forelse ($subject->subjectAreas as $subjectArea)
                            <li>
                                <input type="checkbox" name="subjectareaids[]" value="{{ $subjectArea->subjectareaid }}" {{ ( in_array($subjectArea->subjectareaid, $selectedSubjectAreas)) ? 'checked' : '' }}> {{ $subjectArea->displayname }}
                            </li>
                            @empty
                            @endforelse
                        </ul>
                    </li>
                    @empty
                    @endforelse
                </ul>
            </div>
            
            
            <div class="row mb-2">
                <div class="col text-left">
                    <a class="btn btn-primary" href='{{ url()->previous() }}'>Cancel</a>
                </div>
                <div class="col text-right">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

        </form>
    </div>
</div>
<br/><br/>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();

        $('.subject-selector').change(function() {
            if(this.checked) {
                $( "." + this.value).show();
            } else {
                $( "." + this.value).hide();
            }
        });

        $(".subject-selector:checked").trigger("change");
    });
</script>
@endsection
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<style>
    .uper {
        margin-top: 40px;
    }

    .subject-areas-selector {
        display: none;
    }

    .btn-primary {
        background-color:#607a9b;
        border-color: #607a9b;
    
    }
    .card-body label {
        font-weight: bolder;
    }
</style>
@endsection
@section('scripts')
    
@endsection