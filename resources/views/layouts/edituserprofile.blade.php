<!-- Edit user modal --->
<form action="{{url('/user_management/updateuser')}}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="modal modal-primary fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="userEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-with-subtitle text-primary">
                        <h5 class="modal-title fs-26" id="userEditModalLabel">Edit User</h5>
                        <div class="modal-desc">Edit user data.</div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-7">
                            <h3 class="fs-22 font-weight-semibold mb-3">User Details</h3>
                            <div class="form-group mb-4">
                                <label class="sr-only" for="firstname">First Name:</label>
                                <input type="text" placeholder="First Name" name="firstname" class="form-control text-primary" value="{{ Auth::user()->firstname }}">
                            </div>
                            <div class="form-group mb-4">
                                <label class="sr-only" for="lastname">Last Name:</label>
                                <input type="text" placeholder="Last Name" name="lastname" class="form-control text-primary" value="{{ Auth::user()->lastname }}">
                            </div>

                            <h3 class="fs-22 font-weight-semibold mb-3">Password</h3>
                            <div class="alert alert-primary mt-2" role="alert">
                                Change your password here. Leave blank if you don't want to make any change.
                            </div>
                            <div class="form-group mb-4">
                                <label class="sr-only" for="password">Password:</label>
                                <input type="password" placeholder="Password" name="password" class="form-control text-primary" value="">
                            </div>
                            <div class="form-group mb-4">
                                <label class="sr-only" for="password_confirmation">Confirm:</label>
                                <input type="password" placeholder="Confirm password" name="password_confirmation" class="form-control text-primary" value="">
                            </div>

                            <h3 class="fs-22 font-weight-semibold mb-3">Preferences</h3>
                            <div class="alert alert-primary mt-2" role="alert">
                                What are the user's prefered areas and education levels?
                            </div>

                            <label for="levels">Education Levels:</label>
                            <select class="multiselect form-control" name="levels[]" multiple="multiple">
                                @foreach ($educationLevels as $levelid => $displayname)
                                    <option 
                                        value="{{ $levelid }}" 
                                        {{ (Auth::user()->education_level_groups->contains('id', $levelid)) ? 'selected' : '' }}>
                                        {{ $displayname }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="subjects">Subject Area:</label>
                            <select class="multiselect form-control" name="subjects[]" multiple="multiple">
                                @foreach ($subjects as $subject)
                                    @foreach ($subject->subjectAreas as $subjectArea)
                                        <option value="{{ $subjectArea->subjectareaid }}" {{ (Auth::user()->subjectareas->contains('subjectareaid', $subjectArea->subjectareaid)) ? 'selected' : '' }}>
                                        {{ $subject->displayname }} > {{ $subjectArea->displayname }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>

                            <label for="userPic">Picture</label>
                            <input type="file" class="form-control-file" id="userPic" name="userpicture">
                        </div>
                    </div>
                </div>
                <div class="modal-footer buttonpane">
                    <button type="button" class="btn btn-mute" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="user-edit-modal-save">Update User</button>
                </div>
            </div>
        </div>
    </div>
</form>