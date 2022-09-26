@include('header')
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">   
                <div class="card-header">Create a New Quiz</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('addquiz') }}">
                        @csrf
                            <!-- Quiz Title -->
                            <div class="row mb-3">
                                <label for="quiz_title" class="col-md-4 col-form-label text-md-end">Quiz Title</label>

                                <div class="col-md-6">
                                    <input type="text" id="quiz_title" name="quiz_title" class="quiz_title" required>
                                </div>
                            </div>

                            <!-- Quiz Description -->
                            <div class="row mb-3">
                                <label for="quiz_desc" class="col-md-4 col-form-label text-md-end">Quiz Description</label>

                                <div class="col-md-6">
                                    <textarea id="quiz_desc" class="quiz_desc" name="quiz_desc" rows="5" cols="40" required></textarea>
                                </div>
                            </div>

                            <!-- Enable Items or not for Quiz-->
                            <div class="row mb-3">
                                <label for="items" class="col-md-4 col-form-label text-md-end">Enable Special Privileges & Items?</label>
                                <input id="items" type="radio" class="items" name="items" value="1" checked="checked">Yes</input>
                                </br></br>
                                <input id="items" type="radio" class="items" name="items" value="0">No</input></br>
                            </div>
                            
                            <!-- Select Game Mode -->
                            <div class="row mb-3">
                                <label for="gamemode_id" class="col-md-4 col-form-label text-md-end">Select a Game Mode</label>
                                <select name="gamemode_id" id="gamemode_id">
                                    @foreach($gamemodes as $gamemode)
                                        <option value="{{$gamemode -> gamemode_id}}">{{$gamemode-> gamemode_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Subject -->
                            <div class="row mb-3">
                                <label for="subject_id" class="col-md-4 col-form-label text-md-end">Select a Subject</label>
                                <select name="subject_id" id="subject_id">
                                    @foreach($subjects as $subject)
                                        <option value="{{$subject -> subject_id}}">{{$subject-> subject_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Time Limit -->
                            <div class="row mb-3">
                                <label for="time_limit" class="col-md-4 col-form-label text-md-end">Time Limit: </label>
                                <div class="col-8">
                                    <input id="time_limit" name="time_limit" type="number" step="1" required="required" min="30" max="180"
                                    value="time_limit" placeholder="30" class="form-control">    
                                </div>
                            </div>

                            <!-- Select Groups -->
                            <div class="row mb-3">
                                <label for="group_id" class="col-md-4 col-form-label text-md-end">Select a Group (Optional)</label>
                                <select name="group_id" id="group_id">
                                    <option value="">None</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group -> group_id}}">{{$group-> group_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Continue') }}
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