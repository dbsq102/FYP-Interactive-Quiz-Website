@include('sidebar')
        <div class="queseditor" align="center">
            <div class ="queseditor-header">{{$currQuiz->quiz_title}}</div>
                <div class="queseditor-content">
                    <h2>Question {{Session::get('quesNo')}} out of {{$quesCount}}</h2>
                    <form method="POST" action="{{ route('update-ques-type') }}">
                        @csrf
                        <label for="question_type" class="col-md-4 col-form-label text-md-end">Question Type:</label></br>
                        <select name="question_type" id="question_type">
                            @foreach($questype as $question_type)
                                @if ($question_type->gamemode_id == $currQuiz->gamemode_id || $currQuiz->gamemode_id == 3)
                                    @if ($question_type->type_id == $currQues->type_id)
                                        <option value="{{$question_type-> type_id}}" selected="selected">{{$question_type-> type_name}}</option>
                                    @else
                                        <option value="{{$question_type-> type_id}}">{{$question_type-> type_name}}</option>
                                    @endif        
                                @endif
                            @endforeach
                        </select><br><br>
                        <div align="center">
                            <button onclick="return confirm('Changes to question type will reset all answers for that question.\nProceed?')" type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                            <br><br>
                        </div>
                    </form>
                    <!--Display if question type is multi-choice-->
                    @if($currQues->type_id == 1 || $currQues->type_id == 4)
                        <form method="POST "action="{{route('save-multi-choice')}}">
                            @csrf
                            <!--Display if question title exists-->
                            @if($currQues->question != NULL)
                                <input type="text" class="question_title" id="question_title" name="question_title" value="{{$currQues->question}}" size="100" required><br><br>
                            @else
                                <input type="text" class="question_title" id="question_title" name="question_title" placeholder="Question Title" size="100" required><br><br>
                            @endif
                            @if($currQuesAns->count() == 0)
                                <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Answer 1" required>
                                <input type="radio" id="correct" name="correct" value="1" checked="checked">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer2" id="answer2" name="answer2" placeholder="Answer 2" required>
                                <input type="radio" id="correct" name="correct" value="2">
                                <label for="correct">Correct</label><br><br>
                                <input type="text" class="answer3" id="answer3" name="answer3" placeholder="Answer 3" required>
                                <input type="radio" id="correct" name="correct" value="3">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer4" id="answer4" name="answer4" placeholder="Answer 4" required>
                                <input type="radio" id="correct" name="correct" value="4">
                                <label for="correct">Correct</label><br><br>
                            @else
                                <!--Display Answer 1 if exists-->
                                @if($currQuesAns[0]->answer != NULL)
                                    <input type="text" class="answer1" id="answer1" name="answer1" value="{{$currQuesAns[0]->answer}}" required>
                                    @if($currQuesAns[0]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="1" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="1">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Answer 1" required>
                                    <input type="radio" id="correct" name="correct" value="1" checked="checked">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Answer 2 if exists-->
                                @if($currQuesAns[1]->answer != NULL)
                                <input type="text" class="answer2" id="answer2" name="answer2" value="{{$currQuesAns[1]->answer}}" required>
                                    @if($currQuesAns[1]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="2" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="2">
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer2" id="answer2" name="answer2" placeholder="Answer 2" required>
                                    <input type="radio" id="correct" name="correct" value="2">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                                <!--Display Answer 3 if exists-->
                                @if($currQuesAns[2]->answer != NULL)
                                <input type="text" class="answer3" id="answer3" name="answer3" value="{{$currQuesAns[2]->answer}}" required>
                                    @if($currQuesAns[2]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="3" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="3">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer3" id="answer3" name="answer3" placeholder="Answer 3" required>
                                    <input type="radio" id="correct" name="correct" value="3">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Answer 4 if exists-->
                                @if($currQuesAns[3]->answer != NULL)
                                <input type="text" class="answer4" id="answer4" name="answer4" value="{{$currQuesAns[3]->answer}}" required>
                                    @if($currQuesAns[3]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="4" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="4">
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer4" id="answer4" name="answer4" placeholder="Answer 4" required>
                                    <input type="radio" id="correct" name="correct" value="4">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                            @endif
                            <div align="center">
                                <button type="submit" name="button" class="btn btn-primary" value="save">
                                    {{ __('Save Question') }}
                                </button>
                            </div>
                        </form><br>
                        @if (Session::get('quesNo') != 1)
                        <form class="previous"method="POST" action="{{route('prev-question')}}">
                            @csrf
                            <button type="submit" name="button" class="btn btn-primary" value="previous">
                                {{ __('Previous Question') }}
                            </button>
                        </form>
                        @endif
                        <form class="next" method="POST" action="{{route('next-question')}}">
                            @csrf
                            <button type="submit" name="button" class="btn btn-primary" value="next">
                                {{ __('Next Question') }}
                            </button>
                        </form>
                    <!-- Display if question type is multiple answers -->
                    @elseif($currQues->type_id == 2)
                        <form method="POST "action="{{route('save-sel-multi-ans')}}">
                            @csrf
                            <!--Display if question title exists-->
                            @if($currQues->question != NULL)
                                <input type="text" class="question_title" id="question_title" name="question_title" value="{{$currQues->question}}" size="100" required><br><br>
                            @else
                                <input type="text" class="question_title" id="question_title" name="question_title" placeholder="Question Title" size="100" required><br><br>
                            @endif
                            @if($currQuesAns->count() == 0)
                                <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Answer 1">
                                <input type="checkbox" id="correct1" name="correct1" value="1">
                                <label for="correct1">Correct</label>
                                <input type="text" class="answer2" id="answer2" name="answer2" placeholder="Answer 2">
                                <input type="checkbox" id="correct2" name="correct2" value="1">
                                <label for="correct2">Correct</label><br><br>
                                <input type="text" class="answer3" id="answer3" name="answer3" placeholder="Answer 3">
                                <input type="checkbox" id="correct3" name="correct3" value="1">
                                <label for="correct3">Correct</label>
                                <input type="text" class="answer4" id="answer4" name="answer4" placeholder="Answer 4">
                                <input type="checkbox" id="correct4" name="correct4" value="1">
                                <label for="correct4">Correct</label><br><br>
                            @else
                                <!--Display Answer 1 if exists-->
                                @if($currQuesAns[0]->answer != NULL)
                                    <input type="text" class="answer1" id="answer1" name="answer1" value="{{$currQuesAns[0]->answer}}" required>
                                    @if($currQuesAns[0]->correct == 1)
                                    <input type="checkbox" id="correct1" name="correct1" value="1" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                    <input type="checkbox" id="correct1" name="correct1" value="1">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Answer 1" required>
                                    <input type="checkbox" id="correct1" name="correct1" value="1">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Answer 2 if exists-->
                                @if($currQuesAns[1]->answer != NULL)
                                <input type="text" class="answer2" id="answer2" name="answer2" value="{{$currQuesAns[1]->answer}}" required>
                                    @if($currQuesAns[1]->correct == 1)
                                        <input type="checkbox" id="correct2" name="correct2" value="1" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="checkbox" id="correct2" name="correct2" value="1" >
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer2" id="answer2" name="answer2" placeholder="Answer 2" required>
                                    <input type="checkbox" id="correct2" name="correct2" value="1">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                                <!--Display Answer 3 if exists-->
                                @if($currQuesAns[2]->answer != NULL)
                                <input type="text" class="answer3" id="answer3" name="answer3" value="{{$currQuesAns[2]->answer}}" required>
                                    @if($currQuesAns[2]->correct == 1)
                                        <input type="checkbox" id="correct3" name="correct3" value="1"  checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="checkbox" id="correct3" name="correct3" value="1">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer3" id="answer3" name="answer3" placeholder="Answer 3" required>
                                    <input type="checkbox" id="correct3" name="correct3" value="1">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Answer 4 if exists-->
                                @if($currQuesAns[3]->answer != NULL)
                                <input type="text" class="answer4" id="answer4" name="answer4" value="{{$currQuesAns[3]->answer}}" required>
                                    @if($currQuesAns[3]->correct == 1)
                                        <input type="checkbox" id="correct4" name="correct4" value="1" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="checkbox" id="correct4" name="correct4" value="1">
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer4" id="answer4" name="answer4" placeholder="Answer 4" required>
                                    <input type="checkbox" id="correct4" name="correct4" value="1">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                            @endif
                            <div align="center">
                                <button type="submit" name="button" class="btn btn-primary" value="save">
                                    {{ __('Save Question') }}
                                </button>
                            </div>
                        </form>
                        @if (Session::get('quesNo') != 1)
                        <form class="previous"method="POST" action="{{route('prev-question')}}">
                            @csrf
                            <button type="submit" name="button" class="btn btn-primary" value="previous">
                                {{ __('Previous Question') }}
                            </button>
                        </form>
                        @endif
                        <form class="next" method="POST" action="{{route('next-question')}}">
                            @csrf
                            <button type="submit" name="button" class="btn btn-primary" value="next">
                                {{ __('Next Question') }}
                            </button>
                        </form>
                    <!-- Display if question type has 9 answers -->
                    @elseif($currQues->type_id == 3 || $currQues->type_id == 5)
                        <form method="POST "action="{{route('save-card')}}">
                            @csrf
                            <!--Display if question title exists-->
                            @if($currQues->question != NULL)
                                <input type="text" class="question_title" id="question_title" name="question_title" value="{{$currQues->question}}" size="100" required><br><br>
                            @else
                                <input type="text" class="question_title" id="question_title" name="question_title" placeholder="Question Title" size="100" required><br><br>
                            @endif
                            @if($currQuesAns->count() == 0)
                                <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Card 1" required>
                                <input type="radio" id="correct" name="correct" value="1" checked="checked">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer1" id="answer2" name="answer2" placeholder="Card 2" required>
                                <input type="radio" id="correct" name="correct" value="2">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer1" id="answer3" name="answer3" placeholder="Card 3" required>
                                <input type="radio" id="correct" name="correct" value="3">
                                <label for="correct">Correct</label><br><br>
                                <input type="text" class="answer1" id="answer4" name="answer4" placeholder="Card 4" required>
                                <input type="radio" id="correct" name="correct" value="4">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer1" id="answer5" name="answer5" placeholder="Card 5" required>
                                <input type="radio" id="correct" name="correct" value="5">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer1" id="answer6" name="answer6" placeholder="Card 6" required>
                                <input type="radio" id="correct" name="correct" value="6">
                                <label for="correct">Correct</label><br><br>
                                <input type="text" class="answer1" id="answer7" name="answer7" placeholder="Card 7" required>
                                <input type="radio" id="correct" name="correct" value="7">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer1" id="answer8" name="answer8" placeholder="Card 8" required>
                                <input type="radio" id="correct" name="correct" value="8">
                                <label for="correct">Correct</label>
                                <input type="text" class="answer1" id="answer9" name="answer9" placeholder="Card 9" required>
                                <input type="radio" id="correct" name="correct" value="9">
                                <label for="correct">Correct</label><br><br>
                            @else
                                <!--Display Card 1 if exists-->
                                @if($currQuesAns[0]->answer != NULL)
                                    <input type="text" class="answer1" id="answer1" name="answer1" value="{{$currQuesAns[0]->answer}}" required>
                                    @if($currQuesAns[0]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="1" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="1">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Card 1" required>
                                    <input type="radio" id="correct" name="correct" value="1" checked="checked">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Card 2 if exists-->
                                @if($currQuesAns[1]->answer != NULL)
                                <input type="text" class="answer2" id="answer2" name="answer2" value="{{$currQuesAns[1]->answer}}" required>
                                    @if($currQuesAns[1]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="2" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="2">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer2" id="answer2" name="answer2" placeholder="Card 2" required>
                                    <input type="radio" id="correct" name="correct" value="2">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Card 3 if exists-->
                                @if($currQuesAns[2]->answer != NULL)
                                <input type="text" class="answer3" id="answer3" name="answer3" value="{{$currQuesAns[2]->answer}}" required>
                                    @if($currQuesAns[2]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="3" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="3">
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer3" id="answer3" name="answer3" placeholder="Card 3" required>
                                    <input type="radio" id="correct" name="correct" value="3">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                                <!--Display Card 4 if exists-->
                                @if($currQuesAns[3]->answer != NULL)
                                <input type="text" class="answer4" id="answer4" name="answer4" value="{{$currQuesAns[3]->answer}}" required>
                                    @if($currQuesAns[3]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="4" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="4">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer4" id="answer4" name="answer4" placeholder="Card 4" required>
                                    <input type="radio" id="correct" name="correct" value="4">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Card 5 if exists-->
                                @if($currQuesAns[4]->answer != NULL)
                                <input type="text" class="answer5" id="answer5" name="answer5" value="{{$currQuesAns[4]->answer}}" required>
                                    @if($currQuesAns[4]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="5" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="5">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer5" id="answer5" name="answer5" placeholder="Card 5" required>
                                    <input type="radio" id="correct" name="correct" value="5">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Card 6 if exists-->
                                @if($currQuesAns[5]->answer != NULL)
                                <input type="text" class="answer6" id="answer6" name="answer6" value="{{$currQuesAns[5]->answer}}" required>
                                    @if($currQuesAns[5]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="6" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="6">
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer6" id="answer6" name="answer6" placeholder="Card 6" required>
                                    <input type="radio" id="correct" name="correct" value="6">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                                <!--Display Card 7 if exists-->
                                @if($currQuesAns[6]->answer != NULL)
                                <input type="text" class="answer7" id="answer7" name="answer7" value="{{$currQuesAns[6]->answer}}" required>
                                    @if($currQuesAns[6]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="7" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="7">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer7" id="answer7" name="answer7" placeholder="Card 7" required>
                                    <input type="radio" id="correct" name="correct" value="7">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Card 8 if exists-->
                                @if($currQuesAns[7]->answer != NULL)
                                <input type="text" class="answer8" id="answer8" name="answer8" value="{{$currQuesAns[7]->answer}}" required>
                                    @if($currQuesAns[7]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="8" checked="checked">
                                        <label for="correct">Correct</label>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="8">
                                        <label for="correct">Correct</label>
                                    @endif
                                @else
                                    <input type="text" class="answer8" id="answer8" name="answer8" placeholder="Card 8" required>
                                    <input type="radio" id="correct" name="correct" value="8">
                                    <label for="correct">Correct</label>
                                @endif
                                <!--Display Card 9 if exists-->
                                @if($currQuesAns[8]->answer != NULL)
                                <input type="text" class="answer9" id="answer9" name="answer9" value="{{$currQuesAns[8]->answer}}" required>
                                    @if($currQuesAns[8]->correct == 1)
                                        <input type="radio" id="correct" name="correct" value="9" checked="checked">
                                        <label for="correct">Correct</label><br><br>
                                    @else
                                        <input type="radio" id="correct" name="correct" value="9">
                                        <label for="correct">Correct</label><br><br>
                                    @endif
                                @else
                                    <input type="text" class="answer9" id="answer9" name="answer9" placeholder="Card 9" required>
                                    <input type="radio" id="correct" name="correct" value="9">
                                    <label for="correct">Correct</label><br><br>
                                @endif
                            @endif                    
                            <div align="center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Question') }}
                                </button>
                            </div>
                        </form>
                        @if (Session::get('quesNo') != 1)
                        <form class="previous" method="POST" action="{{route('prev-question')}}">
                            @csrf
                            <button type="submit" name="button" class="btn btn-primary" value="previous">
                                {{ __('Previous Question') }}
                            </button>
                        </form>
                        @endif
                        <form class="next" method="POST" action="{{route('next-question')}}">
                            @csrf
                            <button type="submit" name="button" class="btn btn-primary" value="next">
                                {{ __('Next Question') }}
                            </button>
                        </form>
                        @if (Session::get('quesNo') != 1)
                        <br><br>
                        <div class="btnDelete">
                            <form class="delete" method="POST" action="{{route('delete-question') }}">
                                @csrf
                                <button type="submit" name="button" class="btn btn-primary" value="delete">
                                    {{ __('Delete Question') }}
                                </button>
                            </form>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        
        <!--JavaScripts-->
        <script>
            function quesTypeSelect() {
                let userSelection = document.getElementById("question-type");
                userSelection.addEventListener("change", function() {
                    
                    location.reload();
                })
            }
        </script>
        <!--Script that changes the time limit form to read only if gamemode does not use time limits-->
        <script>
        document.getElementById('update_gamemode_id').onchange = function() {
            if (this.value == 2 || this.value == 3) {
                var time_limit = document.getElementById('update_time_limit');
                time_limit.readOnly = true;
            } else {
                document.getElementById('update_time_limit').readOnly = false;
            }
        }
        </script>
    </body>
</html>