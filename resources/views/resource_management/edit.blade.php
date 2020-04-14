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
            Edit resource.
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
        <form id="create_resource_form" method="post" action='{{url("/resource_management/{$resource->resourceid}")}}'>
            @method('PATCH')
            @csrf
            <input type="hidden" name="resourceid" value="{{ $resource->resourceid }}" />
            <input type="hidden" name="mediatype" value="{{ (isset($resource->mediatype)) ? $resource->mediatype : 'text' }}" id="frmmediatype" />
            @foreach ($resource->files as $file)
            <input name="resourcefiles[]" value="{!! htmlspecialchars(json_encode($file)) !!}" type="hidden">
            @endforeach

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
                            <option value="resource" {{ ($resource->type == 'resource') ? 'selected' : '' }}>Resource</option>
                            <option value="collection" {{ ($resource->type == 'collection') ? 'selected' : '' }}>Collection</option>
                        </select>
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $resource->title }}" placeholder="Resource title..." />
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description">{{ $resource->description }}</textarea>
                    </div>
                    <div id="step-2" class="">
                        <h3 class="border-bottom border-gray pb-2">Step 2 Create the content</h3>
                        <div class="alert alert-primary" role="alert">
                            Compose the main content of your resource here. Feel free to use text, images, videos and links to outside resources.
                        </div>
                        <textarea name="content" id="elm1">{{ $resource->content }}</textarea>
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
                            <option value="{{ $key }}" {{ ( in_array($key, $selectedLevels)) ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>

                        <label for="subjectids">Subject Area:</label>
                        <select class="multiselect form-control" name="subjectids[]" multiple="multiple">
                            <option>Select A Subject Area</option>
                            @foreach ($subjects as $key => $value)
                            <option value="{{ $key }}"{{ ( in_array($key, $selectedSubjects)) ? 'selected' : '' }} >
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>

                        <label for="keywords">Keywords</label>
                        <input type="text" name="keywords" class="form-control" value="{{ $resource->keywords }}" placeholder="Type in some keywords...">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/jquery.smartWizard.min.js')}}"></script>
<script src="{{asset('js/tinymce_4.3.2_jquery/tinymce.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script type="text/javascript">
    var CURRIKI_API_URL = "{{ env('CURRIKI_API_URL') }}";

    $(document).ready(function() {
        // Toolbar extra buttons
        var btnFinish = $('<button></button>').text('Finish')
                                         .addClass('btn btn-info')
                                         .on('click', function(){ $('#create_resource_form').submit(); return false; });
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

        var trusted = '{{ $trusted }}';

        tinymce.init({
            setup: function(ed) {
                ed.on('change', function(e) {
                    //           console.log('the event object ', e);
                    //           console.log('the editor object ', ed);
                    //           console.log('the content ', ed.getContent());
                });
            },
            language: "en",
            selector: "textarea#elm1",
            theme: "modern",
            width: '99.5%',
            height: '600',
            subfolder: "",
            enableLodeStar: trusted,
            relative_urls: false,
            statusbar: false,
            extended_valid_elements: 'a[accesskey|charset|class|contenteditable|contextmenu|coords|dir|download|draggable|dropzone|hidden|href|hreflang|id|lang|media|name|rel|rev|shape|spellcheck|style|tabindex|target|title|translate|type|onclick|onfocus|onblur],button[onclick|class|title],pre',
            plugins: [
                /*gdocsviewer video*/
                //        oembed
                /* "noneditable fileuploader quiz", */
                "noneditable fileuploader",
                'advlist autolink lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars fullscreen',
                'insertdatetime nonbreaking save table contextmenu directionality',
                'emoticons paste textcolor colorpicker textpattern imagetools '


                /*
                 "advlist autolink lists charmap print hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor"
                 */
            ],
            content_css: [
                //        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/misc.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/font-awesome.min.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/style.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/legacy.css',
                //        baseurl + 'wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css',
                //        baseurl + 'wp-content/plugins/bbpress/templates/default/css/bbpress.css',
                //        baseurl + 'wp-content/plugins/buddypress/bp-activity/css/mentions.min.css',
                //        baseurl + 'wp-content/plugins/tablepress/css/default.min.css',
                //        baseurl + 'wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.4.2',
                //        baseurl + 'wp-content/themes/genesis-curriki/css/curriki-custom-style.css'
                'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/jquery.tooltip.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/bbpress/templates/default/css/bbpress.css?ver=2.5.8-5815',
                'https://www.curriki.org/wp-content/plugins/buddypress/bp-activity/css/mentions.min.css?ver=2.3.4',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/misc.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/tablepress/css/default.min.css?ver=1.6.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/font-awesome.min.css?ver=4.3.0',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/style.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/plugins/jetpack/_inc/genericons/genericons/genericons.css?ver=3.1',
                'https://www.curriki.org/wp-content/plugins/jetpack/css/jetpack.css?ver=3.7.2',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/src/skin-win7/ui.fancytree.css',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/lib/prettify.css',
                //        'https://www.curriki.org/wp-content/plugins/addthis/css/output.css?ver=4.3.1',
                'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5&ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/qtip2_v2.2.1/jquery.qtip.min.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/curriki-custom-style.css?ver=4.3.1',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
                //        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
                'https://www.curriki.org/wp-content/themes/genesis-curriki/css/questions_tinymce.css?ver=4.3.1',
            ],
            /* toolbar1: "oembed image video gdoc lodestar quiz | embed emoticons insertdatetime | newdocument undo redo |  cut copy paste searchreplace | spellchecker fullscreen print preview visualblocks visualchars|", */
            toolbar1: "oembed image video gdoc lodestar | embed emoticons insertdatetime | newdocument undo redo |  cut copy paste searchreplace | spellchecker fullscreen print preview visualblocks visualchars|",
            toolbar2: "styleselect fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ltr rtl "
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