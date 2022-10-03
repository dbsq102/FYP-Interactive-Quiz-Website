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
                            @if ($currQues->type_id == 1 || $currQues->type_id == 4)
                                @foreach ($currQuesAns as $answer)
                                    @if($answer->correct == 1)
                                    <a class="ansButton{{$answer->ans_no}}" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                    onclick="return alert('Correct!')">{{$answer->answer}}</a>
                                    @else
                                    <a class="ansButton{{$answer->ans_no}}" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                    onclick="return alert('Sorry, your answer is incorrect.')">{{$answer->answer}}</a>
                                    @endif
                                    @if($loop->iteration % 2 == 0)
                                    <br><br>
                                    @endif
                                @endforeach
                            @elseif ($currQues->type_id == 3)
                                @if (Session::has('ansPhase'))
                                    @foreach ($currQuesAns as $answer)
                                        @if($answer->correct == 1)
                                        <div class="ansCard2" id="answer">
                                            <a id="answer{{$answer->ans_no}}" class="cardLink" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                            onclick="return alert('Correct!')">
                                            <img src="{{asset('/images/cover-card.png')}}" id="image" style="width:200px" >
                                            <p id="ansText" class="hiddenText" hidden>{{$answer->answer}}</p></a>
                                        </div>
                                        @else
                                        <div class="ansCard2" id="answer">
                                            <a id="answer{{$answer->ans_no}}" class="cardLink" href="{{route('check-answer', $answer->correct)}}" value="{{$answer->correct}}"
                                            onclick="return alert('Sorry, your answer is incorrect.')">
                                            <img src="{{asset('/images/cover-card.png')}}" id="image" style="width:200px" >
                                            <p id="ansText" class="hiddenText" hidden>{{$answer->answer}}</p></a>
                                        </div>
                                        @endif
                                        @if($loop->iteration % 3 == 0)
                                        <br>
                                        @endif
                                        <!--
                                        <div class="flip-card">
                                            <div class="flip-card-inner">
                                                <div class="flip-card-front">
                                                <a id="answer{{$answer->ans_no}}" class="cardLink" href="" value="{{$answer->correct}}">
                                                <img src="{{asset('/images/cover-card.png')}}" id="image" style="width:100px" ></a>
                                                </div>
                                                <div class="flip-card-back">
                                                    <p id="ansText">{{$answer->answer}}</p>
                                                </div>
                                            </div>
                                        </div>-->
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
        <!--Card clicked-->
        <script>
            /*($(document).on("click", ".cardLink", function() {
                if(document.value == '1') {
                    return alert('Correct!');
                } else {
                    return alert('Sorry, but your answer is incorrect.');
                }
            });*/
            function flipCard() {
                this.getElement;
                this.src = "{{asset('/images/blank-card.png')}}"
                this.removeAttr('hidden');
            }
        </script>

        @if($currQues->type_id == 3)
            <!--Script for card flipping questions-->
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
        @if($currQues->type_id == 4)
        <!--Script for quickfire questions-->
        <script>
        $(document).ready(function(){
            //COUNTDOWN TIMER
            var timeleft = 5;
            var timer = setInterval(function(){
            if(timeleft <= 0){
                clearInterval(timer);
                alert("Uh oh, you ran out of time!");
                window.location.href="{{route('check-answer', 0) }}";
            } else {
                document.getElementById("countdown").innerHTML = timeleft + " seconds to answer";
            }
            timeleft -= 1;
            }, 1000);

            //COUNTDOWN BAR

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
        });    
        </script>
        @endif
    </body>
</html>