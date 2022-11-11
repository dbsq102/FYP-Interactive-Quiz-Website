        @include('header')
        <br>
        <div class="sidebar">
            <!-- Sidebar for editing quiz -->
            <div class="sidebar-container">   
                <div class="sidebar-header">Edit Quiz Sidebar</div>
                    <div class="sidebar-content">
                        <form method="POST" action="{{ route('updatequiz') }}">
                        @csrf
                            <!-- Quiz Title -->
                            <div class="row mb-3">
                                <label for="update_quiz_title" class="col-md-4 col-form-label text-md-end">Quiz Title: </label>
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
                                <label for="update_quiz_desc" class="col-md-4 col-form-label text-md-end">Quiz Description: </label>
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
                            
                            <!-- Select Game Mode -->
                            <div class="row mb-3">
                                <label for="update_gamemode_id" class="col-md-4 col-form-label text-md-end">Select a Game Mode: </label>
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
                                <label for="update_subject_id" class="col-md-4 col-form-label text-md-end">Select a Subject: </label>
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
                                    @if($currQuiz->gamemode_id == 2 || $currQuiz->gamemode_id == 3)
                                    <input id="update_time_limit" name="update_time_limit" type="number" step="1" required="required" min="30" max="300"
                                    value="{{$currQuiz -> time_limit}}" class="form-control" readOnly="true">  
                                    @else
                                    <input id="update_time_limit" name="update_time_limit" type="number" step="1" required="required" min="30" max="300"
                                    value="{{$currQuiz -> time_limit}}" class="form-control">  
                                    @endif
                                </div>
                            </div>

                            <!-- Select Groups -->
                            <div class="row mb-3">
                                <label for="update_group_id" class="col-md-4 col-form-label text-md-end">Select a Group (Optional): </label>
                                <select name="update_group_id" id="update_group_id">
                                    <option value="">None</option>
                                    @foreach($groups as $group)
                                        @if($group->user_id == Auth::id())
                                            @if($group->group_id == $currQuiz->group_id)
                                                <option value="{{$group -> group_id}}" selected="selected">{{$group-> group_name}}</option>
                                            @else
                                                <option value="{{$group -> group_id}}">{{$group-> group_name}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    @if($currQuiz->gamemode_id == 3)
                                        <button type="submit" onclick="return confirm('Changing game mode from Mixed will reset all unrelated questions.\nProceed?')" class="btn btn-primary">
                                            {{ __('Save Settings') }}
                                        </button> 
                                    @else
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Save Settings') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>