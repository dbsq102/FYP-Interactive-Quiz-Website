@include('header')
        <br>
        <div class="sidebar">
            <div class="card">   
                <div class="card-header">Edit Quiz {{Session::get('quizID')}}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('updatequiz') }}">
                    @csrf
                        <!-- Quiz Description -->
                        <div class="row mb-3">
                            <label for="update_quiz_title" class="col-md-4 col-form-label text-md-end">Quiz Title</label>
                            @if(!empty($currQuiz->quiz_title))
                            <div class="col-md-6">
                                <input type="text" id="update_quiz_title" name="update_quiz_title" class="update_quiz_title" value="{{$currQuiz->quiz_title}}"required>
                            </div>
                            @else
                            <div class="col-md-6">
                                <input type="text" id="update_quiz_title" name="update_quiz_title" class="update_quiz_title" required>
                            </div>
                            @endif
                        </div>
                        <!-- Quiz Description -->
                        <div class="row mb-3">
                            <label for="update_quiz_desc" class="col-md-4 col-form-label text-md-end">Quiz Description</label>
                            @if(!empty($currQuiz->quiz_summary))
                            <div class="col-md-6">
                                <textarea id="update_quiz_desc" class="update_quiz_desc" name="update_quiz_desc" rows="5" cols="20" required>{{$currQuiz -> quiz_summary}}</textarea>
                            </div>
                            @else
                            <div class="col-md-6">
                                <textarea id="update_quiz_desc" class="update_quiz_desc" name="update_quiz_desc" rows="5" cols="20" required></textarea>
                            </div>
                            @endif
                        </div>

                        <!-- Enable Items or not for Quiz-->
                        <div class="row mb-3">
                            <label for="update_items" class="col-md-4 col-form-label text-md-end">Enable Special Privileges & Items?</label>
                            @if ($currQuiz->items == 1)
                            <input id="update_items" type="radio" class="update_items" name="update_items" value="1" checked="checked">Yes</input>
                            </br></br>
                            <input id="update_items" type="radio" class="update_items" name="update_items" value="0">No</input></br>
                            @else ($currQuiz->items == 0)
                            <input id="update_items" type="radio" class="update_items" name="update_items" value="1">Yes</input>
                            </br></br>
                            <input id="update_items" type="radio" class="update_items" name="update_items" value="0" checked="checked">No</input></br>
                            @endif
                        </div>
                        
                        <!-- Select Game Mode -->
                        <div class="row mb-3">
                            <label for="update_gamemode_id" class="col-md-4 col-form-label text-md-end">Select a Game Mode</label>
                            <select name="update_gamemode_id" id="update_gamemode_id">
                                @foreach($gamemodes as $gamemode)
                                    @if($gamemode->gamemode_id == $currQuiz->gamemode_id)
                                    <option value="{{$gamemode -> gamemode_id}}" selected="selected">{{$gamemode-> gamemode_name}}</option>
                                    @else
                                    <option value="{{$gamemode -> gamemode_id}}">{{$gamemode-> gamemode_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Subject -->
                        <div class="row mb-3">
                            <label for="update_subject_id" class="col-md-4 col-form-label text-md-end">Select a Subject</label>
                            <select name="update_subject_id" id="update_subject_id">
                                @foreach($subjects as $subject)
                                    @if($subject->subject_id == $currQuiz->subject_id)
                                    <option value="{{$subject -> subject_id}}" selected="selected">{{$subject-> subject_name}}</option>
                                    @else
                                    <option value="{{$subject -> subject_id}}">{{$subject-> subject_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Time Limit -->
                        <div class="row mb-3">
                            <label for="update_time_limit" class="col-md-4 col-form-label text-md-end">Time Limit: </label>
                            <div class="col-8">
                                <input id="update_time_limit" name="update_time_limit" type="number" step="1" required="required" min="30" max="180"
                                value="{{$currQuiz -> time_limit}}" class="form-control">  
                            </div>
                        </div>

                        <!-- Select Groups -->
                        <div class="row mb-3">
                            <label for="update_group_id" class="col-md-4 col-form-label text-md-end">Select a Group (Optional)</label>
                            <select name="update_group_id" id="update_group_id">
                                <option value="">None</option>
                                @foreach($groups as $group)
                                    <option value="{{$group -> group_id}}">{{$group-> group_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Settings') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function quesTypeSelect() {
                let userSelection = document.getElementById("question-type");
                userSelection.addEventListener("change", function() {
                    
                    location.reload();
                })
            }
        </script>
    </body>
</html>