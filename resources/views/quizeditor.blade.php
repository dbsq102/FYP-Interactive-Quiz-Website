@include('sidebar')
        <br>
        <div align="center">
            <h1>{{$currQuiz->quiz_title}}</h1>
            <h2>Question {{Session::get('quesNo')}}</h2>
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
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                    <br><br>
                </div>
            </form>
            @if($currQues->type_id == 1)
                <form method="POST "action="{{route('save-multi-choice')}}">
                    @csrf
                    <input type="text" class="question_title" id="question_title" name="question_title" placeholder="Question Title" size="100" required><br><br>
                    <input type="text" class="answer1" id="answer1" name="answer1" placeholder="Answer 1">
                    <input type="radio" id="correct" name="correct" value="1" checked="checked">
                    <label for="correct">Correct</label>
                    <input type="text" class="answer2" id="answer2" name="answer2" placeholder="Answer 2">
                    <input type="radio" id="correct" name="correct" value="2">
                    <label for="correct">Correct</label><br><br>
                    <input type="text" class="answer3" id="answer3" name="answer3" placeholder="Answer 3">
                    <input type="radio" id="correct" name="correct" value="3">
                    <label for="correct">Correct</label>
                    <input type="text" class="answer4" id="answer4" name="answer4" placeholder="Answer 4">
                    <input type="radio" id="correct" name="correct" value="4">
                    <label for="correct">Correct</label><br><br>
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
            @elseif($currQues->type_id == 2)
                <form>
                    <input type="text" class="question_title" id="question_title" name="question_title" placeholder="Question Title" size="100" required><br><br>
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
            @elseif($currQues->type_id == 3)
                <form>
                    <input type="text" name="question_title" placeholder="Question Title" size="100"><br><br>
                    <input type="text" name="answer1" placeholder="Card 1">
                    <input type="radio" id="correct" name="correct" value="1" checked="checked">
                    <label for="correct">Correct</label>
                    <input type="text" name="answer2" placeholder="Card 2">
                    <input type="radio" id="correct" name="correct" value="2">
                    <label for="correct">Correct</label>
                    <input type="text" name="answer3" placeholder="Card 3">
                    <input type="radio" id="correct" name="correct" value="3">
                    <label for="correct">Correct</label><br><br>
                    <input type="text" name="answer4" placeholder="Card 4">
                    <input type="radio" id="correct" name="correct" value="4">
                    <label for="correct">Correct</label>
                    <input type="text" name="answer5" placeholder="Card 5">
                    <input type="radio" id="correct" name="correct" value="5">
                    <label for="correct">Correct</label>
                    <input type="text" name="answer6" placeholder="Card 6">
                    <input type="radio" id="correct" name="correct" value="6">
                    <label for="correct">Correct</label><br><br>
                    <input type="text" name="answer7" placeholder="Card 7">
                    <input type="radio" id="correct" name="correct" value="7">
                    <label for="correct">Correct</label>
                    <input type="text" name="answer8" placeholder="Card 8">
                    <input type="radio" id="correct" name="correct" value="8">
                    <label for="correct">Correct</label>
                    <input type="text" name="answer9" placeholder="Card 9">
                    <input type="radio" id="correct" name="correct" value="9">
                    <label for="correct">Correct</label><br><br>
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
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
            @elseif($currQues->type_id == 4)
                <form>
                    <input type="text" name="question_title" placeholder="Question Title" size="100"><br><br>
                    <input type="text" name="answer1" placeholder="Answer 1">
                    <input type="text" name="answer2" placeholder="Answer 2"><br><br>
                    <input type="text" name="answer3" placeholder="Answer 3">
                    <input type="text" name="answer4" placeholder="Answer 4"><br><br>
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
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
            @endif
        </div>
    </body>
</html>