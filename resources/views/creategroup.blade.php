@include('header')
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">   
                <div class="card-header">Create a New Group</div>
                    <div class="card-body">
                        <!-- Add a New Group -->
                        <form method="POST" action="{{ route('create-group') }}">
                        @csrf
                            <!-- Group Name -->
                            <div class="row mb-3">
                                <label for="group_name" class="col-md-4 col-form-label text-md-end">Group Name: </label>
                                <div class="col-md-6">
                                    <input type="text" id="group_name" name="group_name" class="group_name" required>
                                </div>
                            </div>
                            <!-- Group Description -->
                            <div class="row mb-3">
                                <label for="group_desc" class="col-md-4 col-form-label text-md-end">Group Description: </label>
                                <div class="col-md-6">
                                    <textarea id="group_desc" class="group_desc" name="group_desc" rows="5" cols="20" required></textarea>
                                </div>
                            </div>

                            <!-- Enable Public Group or not-->
                            <div class="row mb-3">
                                <label for="public" class="col-md-4 col-form-label text-md-end">Public: </label>
                                <label for="public">Yes</input>
                                <input id="public" type="radio" class="public" name="public" value="1" checked="checked"></input>
                                <label for="public">No</input>
                                <input id="public" type="radio" class="public" name="public" value="0"></input></br>
                            </div>
                            
                            <!-- Select Subject -->
                            <div class="row mb-3">
                                <label for="subject_id" class="col-md-4 col-form-label text-md-end">Select a Subject: </label>
                                <select name="subject_id" id="subject_id" required>
                                    <option value="">Select a subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{$subject -> subject_id}}">{{$subject-> subject_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Create Group') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>