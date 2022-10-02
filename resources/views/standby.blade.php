        @include('header')

        <br><br><br><br>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-8 mx-auto">
                    <div class="text-center">
                        <div class="standby">
                            <h1>You are about to attempt {{$quiz->quiz_title}}.</h1><br>
                            <h2>Game Mode:<br>
                            {{$quiz->gamemode_name}}</h2><br>
                            <h3>Summary:</h2> 
                            <p>{{$quiz->quiz_summary}}</p><br>
                            @if($quiz->gamemode_id == 2 || $quiz->gamemode_id == 3)
                                <p>Each question will allow you 15 seconds to answer. Quickfire questions will allow you 5 seconds to answer.</p>
                            @else
                                <p>The time limit for this quiz is {{$quiz->time_limit}} minutes.</p>
                            @endif
                            <form>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Start Quiz') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>