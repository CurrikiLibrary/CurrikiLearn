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
            @if($collection == null)
                Create new resource.
            @else
                Add resource to: {{ $collection->title }}
            @endif
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
        <form id="update-resource" method="post" action='{{url("/resource_management/1")}}'>
            @method('PATCH')
            @csrf
            <input type="hidden" name="mediatype" value="text" id="frmmediatype" />
            <input type="hidden" name="collection" value="{{($collection != null) ? $collection->resourceid : '' }}" id="frmmediatype" />
            <div id="smartwizard">
                <ul>
                    <li><a href="#step-1">Step 1<br /><small>Describe the resource</small></a></li>
                    <li><a href="#step-2">Step 2<br /><small>Create the content</small></a></li>
                    <li><a href="#step-3">Step 3<br /><small>Classify your resource</small></a></li>
                </ul>

                <div>
                    <div id="step-1" class="">
                        <h3 class="border-bottom border-gray pb-2">Step 1 Describe the resource</h3>
                        <div class="alert alert-primary" role="alert">
                            Provide some information about your resource so other people can find it.
                        </div>
                        <label for="type">Type</label>
                        <select name="resource_type" class="form-control">
                            <option value="resource">Resource</option>
                            <option value="collection">Collection</option>
                        </select>
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Resource title..." />
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div id="step-2" class="">
                        <h3 class="border-bottom border-gray pb-2">Step 2 Create the content</h3>
                        <div class="alert alert-primary" role="alert">
                            Compose the main content of your resource here. Feel free to use text, images, videos and links to outside resources.
                        </div>
                        <textarea name="content" id="content-editor"></textarea>
                    </div>
                    <div id="step-3" class="">
                        <h3 class="border-bottom border-gray pb-2">Step 3 Classify your resource</h3>
                        <div class="alert alert-primary" role="alert">
                            Fill in these options to properly classify your resource in the system.
                        </div>

                        <label for="education_levels">Education Levels:</label>
                        <select class="multiselect form-control" name="education_levels[]" multiple="multiple">
                            <option>Select Level</option>
                            @foreach ($levels as $key => $value)
                            <option value="{{ $key }}">
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>

                        <label for="subjectids">Subject Area:</label>
                        <select class="multiselect form-control" name="subjectids[]" multiple="multiple">
                            <option>Select A Subject Area</option>
                            @foreach ($subjects as $key => $value)
                            <option value="{{ $key }}">
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>

                        <label for="keywords">Keywords</label>
                        <input type="text" name="keywords" class="form-control" placeholder="Type in some keywords...">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/jquery.smartWizard.min.js')}}"></script>
<script src="{{asset('js/tinymce/tinymce.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Toolbar extra buttons
        var btnFinish = $('<button></button>').text('Finish')
                                         .addClass('btn btn-info')
                                         .on('click', function(){ $('#update-resource').submit(); return false; });
        var btnCancel = $('<button></button>').text('Cancel')
                                         .addClass('btn btn-danger')
                                         .on('click', function(){ $('#smartwizard').smartWizard("reset"); });

        $('#smartwizard').smartWizard({
                selected: 0,
                theme: 'circles',
                transitionEffect:'fade',
                showStepURLhash: true,
                toolbarSettings: {toolbarPosition: 'both',
                                  toolbarButtonPosition: 'end',
                                  toolbarExtraButtons: [btnFinish, btnCancel]
                                }
        });

        tinymce.init({
            selector: '#content-editor',
            height: '24em',
            menubar: false,
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | link image',
            plugins: 'link image',
            init_instance_callback: function (editor) {
                editor.on('paste', function (e) {
                    setTimeout(function(){ editor.uploadImages(); }, 1000);
                });
            },
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ env("CURRIKI_API_URL") }}wp-content/themes/genesis-curriki/js/tinymce_4.3.2_jquery/plugins/fileuploader/upload.php');
                xhr.onload = function() {
                    var json;

                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);

                    if (!json) {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    } else if (json.error != '') {
                        failure(json.error);
                        return;
                    }

                    if (jQuery('#frmmediatype').val() == 'text') {
                        switch (json.type) {
                            case 'swf':
                                jQuery('#frmmediatype').val('swf');
                                break;
                            case 'video':
                                jQuery('#frmmediatype').val('video');
                                break;
                            case 'image':
                                jQuery('#frmmediatype').val('image');
                                break;
                            case 'document':
                                jQuery('#frmmediatype').val('document');
                                break;
                            default:
                                jQuery('#frmmediatype').val('attachment');
                                break;
                        }
                    } else
                        jQuery('#frmmediatype').val('mixed');

                    jQuery('<input>').attr({
                        type: 'hidden',
                        value: JSON.stringify(json),
                        name: 'resourcefiles[]'
                    }).prependTo('form#update-resource');

                    success(json.url);
                };

                if( typeof(blobInfo.blob().name) !== 'undefined' )
                    fileName = blobInfo.blob().name;
                else
                    fileName = blobInfo.filename();

                formData = new FormData();
                formData.append('file', blobInfo.blob(), fileName);
                formData.append('type', 'file');
                xhr.send(formData);
            }
        });

        $('.multiselect').select2();

        // Initialize the showStep event
        $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
            if (stepNumber == 2) {
                $('#step-3').show();
                $('.multiselect').select2();
            }
        });
    });
</script>
@endsection
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('css/smart_wizard.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/smart_wizard_theme_circles.min.css')}}" rel="stylesheet" type="text/css" />
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
