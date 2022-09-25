@include('sidebar')
        <br>
        <div class="col-xs-1" align="center">
            <h1>{{$currQuiz->quiz_title}}</h1>
            <h2>Question {{Session::get('quesNo')}}</h2>
            <form method="POST" action="{{ route('update-ques-type') }}">
                <label for="question_type" class="col-md-4 col-form-label text-md-end">Question Type:</label></br>
                <select name="question_type" id="question_type">
                    @foreach($questype as $question_type)
                        @if ($question_type->gamemode_id == $currQuiz->gamemode_id || $currQuiz->gamemode_id == 3)
                            <option value="{{$question_type-> type_name}}">{{$question_type-> type_name}}</option>
                        @endif
                    @endforeach
                </select><br><br>
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                    <br><br>
                </div>
            </form>
            @if($currQuiz->type_id == 1)
            <form>
                <input type="text" name="question_title" placeholder="Question Title"><br><br>
                <input type="text" name="answer1" placeholder="Answer 1">
                <input type="text" name="answer2" placeholder="Answer 2"><br><br>
                <input type="text" name="answer3" placeholder="Answer 3">
                <input type="text" name="answer4" placeholder="Answer 4"><br><br>
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Next Question') }}
                    </button>
                </div>
            </form>
            @elseif($currQuiz->type_id == 3)
            <form>
                <input type="text" name="question_title" placeholder="Question Title" size="100"><br><br>
                <input type="text" name="answer1" placeholder="Card 1">
                <input type="text" name="answer2" placeholder="Card 2">
                <input type="text" name="answer3" placeholder="Card 3"><br><br>
                <input type="text" name="answer4" placeholder="Card 4">
                <input type="text" name="answer5" placeholder="Card 5">
                <input type="text" name="answer6" placeholder="Card 6"><br><br>
                <input type="text" name="answer7" placeholder="Card 7">
                <input type="text" name="answer8" placeholder="Card 8">
                <input type="text" name="answer9" placeholder="Card 9"><br><br>
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Next Question') }}
                    </button>
                </div>
            </form>
            @endif
        </div>
    </body>
</html>