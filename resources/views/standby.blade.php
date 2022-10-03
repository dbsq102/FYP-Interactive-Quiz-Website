        @include('header')

        <br><br><br>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-8 mx-auto">
                    <div class="text-center">
                        <div class="standby">
                            <h1>You are about to attempt <br>{{$quiz->quiz_title}}</h1><br>
                            <h3>Game Mode:<br>
                            {{$quiz->gamemode_name}}</h3><br>
                            <p>Summary:</p> 
                            <p>{{$quiz->quiz_summary}}</p>
                            <p>There are <b>{{$quesCount}}</b> question(s) in this quiz.</p>
                            @if($quiz->gamemode_id == 2 || $quiz->gamemode_id == 3)
                                <p>Each question will allow you 15 seconds to answer. Quickfire questions will allow you 5 seconds to answer.</p>
                            @else
                                <p>The time limit for this quiz is {{$quiz->time_limit}} minutes.</p>
                            @endif
                            <a href="{{route('play-quiz', $quiz->quiz_id)}}" class="btn btn-primary">Start Quiz</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>