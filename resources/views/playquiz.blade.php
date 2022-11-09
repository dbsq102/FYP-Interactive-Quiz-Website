        @include('header')
        <br><br><br><br>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-8 mx-auto">
                    <div class="text-center">
                        <div class="playquiz">    
                            <div id="progressBar">
                                <div></div>
                            </div>
                            <p id="countdown"></p>
                            <h1>Q{{Session::get('playQuesNo')}}: {{$currQues->question}}</h1><br>
                            <!-- Multiple-choice Questions / Quick-Fire Questions -->
                            @if ($currQues->type_id == 1 || $currQues->type_id == 4)
                                @foreach ($currQuesAns as $answer)
                                    @if($answer->correct == 1)
                                    <a id="ans" class="ansButton{{$answer->ans_no}}" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                    onclick="return alert('Correct!')">{{$answer->answer}}</a>
                                    @else
                                    <a id="ans" class="ansButton{{$answer->ans_no}}" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                    onclick="return alert('Sorry, your answer is incorrect.')">{{$answer->answer}}</a>
                                    @endif
                                    @if($loop->iteration % 2 == 0)
                                    <br><br>
                                    @endif
                                @endforeach
                            <!-- Select Multiple Answers-->
                            @elseif ($currQues->type_id == 2)
                                <form method="POST" action="{{route('check-multi-answer')}}">
                                    @csrf
                                    @foreach ($currQuesAns as $answer)                                
                                        <label class="labelAns" for="ans{{$answer->ans_no}}">{{$answer->answer}}</label>
                                        @if($answer->correct == 1)
                                        <input type="checkbox" id="ans" name="ans{{$answer->ans_no}}" class="ans{{$answer->ans_no}}" value="1">
                                        @else
                                        <input type="checkbox" id="ans" name="ans{{$answer->ans_no}}" class="ans{{$answer->ans_no}}" value="0">
                                        @endif
                                        @if($loop->iteration % 2 == 0)
                                        <br><br>
                                        @endif
                                    @endforeach
                                    <button type="submit" id="ans" class="btn btn-primary">Check Answer</button>
                                </form>
                            <!-- Card Flip -->
                            @elseif ($currQues->type_id == 3)
                                @foreach ($currQuesAns as $answer)
                                    <div class="flip-card-container">
                                        <div class="flip-card-content">
                                            <div class="flip-card-front">
                                                <a id="ans{{$answer->ans_no}}" class="cardLink">
                                                <img src="{{asset('/images/cover-card-2.png')}}" id="image" style="width:100px" ></a>
                                            </div>
                                            @if($answer->correct == 1)
                                            <div class="flip-card-back">
                                                <a id="ans{{$answer->ans_no}}" class="cardLink" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                                onclick="return alert('Correct!')"><div class="cardPlay">{{$answer->answer}}</div></a>
                                            </div>
                                            @else
                                            <div class="flip-card-back">
                                                <a id="ans{{$answer->ans_no}}" class="cardLink" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                                onclick="return alert('Sorry, your answer is incorrect.')"><div class="cardPlay">{{$answer->answer}}</div></a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($loop->iteration % 3 == 0)
                                        <br><br>
                                    @endif
                                @endforeach
                            <!-- Memorize Answers -->
                            @elseif ($currQues->type_id == 5)
                                @if (Session::has('ansPhase'))
                                    @foreach ($currQuesAns as $answer)
                                        @if($answer->correct == 1)
                                            <div class="ansCard2" id="answer">
                                                <a id="ans{{$answer->ans_no}}" class="cardLink" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                                onclick="return alert('Correct!')">
                                                <img src="{{asset('/images/cover-card.png')}}" id="image" style="width:200px" ></a>
                                            </div>
                                        @else
                                            <div class="ansCard2" id="answer">
                                                <a id="ans{{$answer->ans_no}}" class="cardLink" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                                onclick="return alert('Sorry, your answer is incorrect.')">
                                                <img src="{{asset('/images/cover-card.png')}}" id="image" style="width:200px" ></a>
                                            </div>
                                        @endif
                                        @if($loop->iteration % 3 == 0)
                                            <br>
                                        @endif
                                    @endforeach
                                @else 
                                    @foreach ($currQuesAns as $answer)
                                        <div class="ansCard">
                                            <img src="{{asset('/images/blank-card.png')}}" style="width:200px">
                                            <div class="cardPlay">{{$answer->answer}}</div>
                                        </div>
                                        @if($loop->iteration % 3 == 0)
                                            <br>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Confirmation dialogue for leaving-->
        <script>
            $(document).on("click", ".nav", function() {
                return confirm('Are you sure you want to leave this ongoing quiz? Changes will not be saved.');
            });
        </script>
        <!-- Change timers for regular question types if the quiz type is mixed -->
        @if($getGamemode == 1)
            <!--Script for regular quiz time limit-->
            <script>
                //Countdown timer
                if(localStorage.getItem('timelimit') === null) {
                    localStorage.setItem('timelimit', <?=Session::get("timelimit") * 60?>);
                }
                var fulltime = <?=Session::get("timelimit") * 60?>;
                var timeleft = localStorage.getItem('timelimit');
                    var timer = setInterval(function(){
                    if(timeleft <= 0){
                        clearInterval(timer);
                        alert("Uh oh, you ran out of time!");
                        localStorage.removeItem('timelimit');
                        window.location.href="{{route('check-answer', 0) }}";
                    } else {
                        $(document).on("click", "#ans", function() {
                            localStorage.setItem('timelimit', timeleft);
                        });
                        $(document).on("click", ".nav", function() {
                            localStorage.removeItem('timelimit');
                        });
                        document.getElementById("countdown").innerHTML = (timeleft/60).toFixed(1) + " minutes to answer";
                    }
                    timeleft -= 1;
                    }, 1000);
                    

                    //Countdown bar
                    function progress(timeleft, timetotal, $element) {
                        var progressBarWidth = timeleft * $element.width() / timetotal;
                        $element.find('div').animate({ width: progressBarWidth }, timeleft == timetotal ? 0 : 1000, 'linear');
                        if(timeleft > 0) {
                            setTimeout(function() {
                                progress(timeleft - 1, timetotal, $element);
                            }, 1000);
                        }
                    };
                    //adjust these numbers to match time set
                    //must be in seconds
                    progress(timeleft, fulltime, $('#progressBar')); 
            </script>
        @else
            @if($currQues->type_id == 1 || $currQues->type_id == 2 || $currQues->type_id == 3)
                <!--Script for normal timers, used in multi-choice, multiple answers and card flip-->
                <script>            
                    //Countdown timer
                    var timeleft = 15;
                    var timer = setInterval(function(){
                    if(timeleft <= 0){
                        clearInterval(timer);
                        alert("Uh oh, you ran out of time!");
                        window.location.href="{{route('check-answer', 0) }}";
                    } else {
                        $(document).on("click", "a", function() {
                            clearInterval(timer);
                        });
                        document.getElementById("countdown").innerHTML = timeleft + " seconds to answer";
                    }
                    timeleft -= 1;
                    }, 1000);
                    

                    //Countdown bar

                    function progress(timeleft, timetotal, $element) {
                        var progressBarWidth = timeleft * $element.width() / timetotal;
                        $element.find('div').animate({ width: progressBarWidth }, timeleft == timetotal ? 0 : 1000, 'linear');
                        if(timeleft > 0) {
                            setTimeout(function() {
                                progress(timeleft - 1, timetotal, $element);
                            }, 1000);
                        }
                    };
                    //adjust these numbers to match time set
                    //must be in seconds
                    progress(15, 15, $('#progressBar')); 
                </script>
            @elseif($currQues->type_id == 4)
                <!--Script for quickfire questions time limit-->
                <script>            
                    //Countdown timer
                    var timeleft = 5;
                    var timer = setInterval(function(){
                    if(timeleft <= 0){
                        clearInterval(timer);
                        alert("Uh oh, you ran out of time!");
                        window.location.href="{{route('check-answer', 0) }}";
                    } else {
                        $(document).on("click", "a", function() {
                            clearInterval(timer);
                        });
                        document.getElementById("countdown").innerHTML = timeleft + " seconds to answer";
                    }
                    timeleft -= 1;
                    }, 1000);
                    

                    //Countdown bar

                    function progress(timeleft, timetotal, $element) {
                        var progressBarWidth = timeleft * $element.width() / timetotal;
                        $element.find('div').animate({ width: progressBarWidth }, timeleft == timetotal ? 0 : 1000, 'linear');
                        if(timeleft > 0) {
                            setTimeout(function() {
                                progress(timeleft - 1, timetotal, $element);
                            }, 1000);
                        }
                    };
                    //adjust these numbers to match time set
                    //must be in seconds
                    progress(5, 5, $('#progressBar')); 
                </script>
            @elseif($currQues->type_id == 5)
                <!--Script for card flipping questions time limit-->
                @if(!Session::has('ansPhase'))
                    <script> 
                    //Phase 1, give 7 seconds to memorize card
                    $(document).ready(function(){

                        //Initial countdown timer
                        var timeleft = 7;
                        var timer = setInterval(function(){
                        if(timeleft <= 0){
                            clearInterval(timer);
                            "{{Session::put('ansPhase', '1')}}"
                            window.location.href="{{route('play-quiz', Session::get('playQuizId'))}}";
                        } else {
                            $(document).on("click", "a", function() {
                                clearInterval(timer);
                            });
                            document.getElementById("countdown").innerHTML = timeleft + " seconds to memorize the cards";
                        }
                        timeleft -= 1;
                        }, 1000);

                        //Initial progress bar

                        function progress(timeleft, timetotal, $element) {
                            var progressBarWidth = timeleft * $element.width() / timetotal;
                            $element.find('div').animate({ width: progressBarWidth }, timeleft == timetotal ? 0 : 1000, 'linear');
                            if(timeleft > 0) {
                                setTimeout(function() {
                                    progress(timeleft - 1, timetotal, $element);
                                }, 1000);
                            }
                        };
                        //adjust these numbers to match time set
                        //must be in seconds
                        progress(7, 7, $('#progressBar'));
                    });    
                    </script>
                @else
                <script>
                    //Phase 2, give 15 seconds to answer
                    $(document).ready(function(){
                        //Second countdown timer
                        var timeleft = 15;
                        var timer = setInterval(function(){
                        if(timeleft <= 0){
                            clearInterval(timer);
                            alert("Uh oh, you ran out of time!");
                            window.location.href="{{route('check-answer', 0) }}";
                        } else {     
                            $(document).on("click", "a", function() {
                                clearInterval(timer);
                            });
                            document.getElementById("countdown").innerHTML = timeleft + " seconds to choose a card";
                        }
                        timeleft -= 1;
                        }, 1000);

                        //Initial progress bar

                        function progress(timeleft, timetotal, $element) {
                            var progressBarWidth = timeleft * $element.width() / timetotal;
                            $element.find('div').animate({ width: progressBarWidth }, timeleft == timetotal ? 0 : 1000, 'linear');
                            if(timeleft > 0) {
                                setTimeout(function() {
                                    progress(timeleft - 1, timetotal, $element);
                                }, 1000);
                            }
                        };
                        //adjust these numbers to match time set
                        //must be in seconds
                        progress(15, 15, $('#progressBar'));
                    });    
                    </script>
                @endif
            @endif
        @endif
        <!-- Script for alerts -->
        <script>
            var msg = "{{Session::get('alert')}}";
            var exist = "{{Session::has('alert')}}";
            if(exist){
                alert(msg);
            }
        </script>
    </body>
</html>