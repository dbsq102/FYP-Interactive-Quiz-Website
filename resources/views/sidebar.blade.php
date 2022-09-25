@include('header')
        <br>
        <div class="sidebar">            
            <div class="col-md-8">
                <div class="card">   
                    <div class="card-header">Edit Quiz</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updatequiz') }}">
                        @csrf
                            <!-- Quiz Description -->
                            <div class="row mb-3">
                                <label for="quiz_desc" class="col-md-4 col-form-label text-md-end">Quiz Description</label>
                                <div class="col-md-6">
                                    <textarea id="quiz_desc" class="quiz_desc" name="quiz_desc" value="quiz_desc" rows="5" cols="20">{{$currQuiz -> quiz_summary}}</textarea>
                                </div>
                            </div>

                            <!-- Enable Items or not for Quiz-->
                            <div class="row mb-3">
                                <label for="items" class="col-md-4 col-form-label text-md-end">Enable Special Privileges & Items?</label>
                                @if ($currQuiz->items == 1)
                                <input id="items" type="radio" class="items" name="items" value="1" checked="checked">Yes</input>
                                </br></br>
                                <input id="items" type="radio" class="items" name="items" value="0">No</input></br>
                                @else ($currQuiz->items == 0)
                                <input id="items" type="radio" class="items" name="items" value="1">Yes</input>
                                </br></br>
                                <input id="items" type="radio" class="items" name="items" value="0" checked="checked">No</input></br>
                                @endif
                            </div>
                            
                            <!-- Select Game Mode -->
                            <div class="row mb-3">
                                <label for="gamemode_id" class="col-md-4 col-form-label text-md-end">Select a Game Mode</label>
                                <select name="gamemode_id" id="gamemode_id">
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
                                <label for="subject_id" class="col-md-4 col-form-label text-md-end">Select a Subject</label>
                                <select name="subject_id" id="subject_id">
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
                                <label for="subject_id" class="col-md-4 col-form-label text-md-end">Time Limit: </label>
                                <div class="col-8">
                                    <input id="time_limit" name="time_limit" type="number" step="1" required="required" min="30" max="180"
                                    value="{{$currQuiz -> time_limit}}" class="form-control">  
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
                                        {{ __('Save Settings') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
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