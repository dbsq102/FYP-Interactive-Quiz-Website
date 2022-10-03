        @include('header')

        <br><br><br><br>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-8 mx-auto">
                    <div class="text-center">
                        <div class="playquiz">
                            <h1>Q{{Session::get('playQuesNo')}}</h1>      
                            <div id="progressBar">
                                <div></div>
                            </div>
                            <p id="countdown"></p>
                            <h1>{{$currQues->question}}</h1>
                            @if ($currQues->type_id == 1 || $currQues->type_id == 4)
                                @foreach ($currQuesAns as $answer)
                                    <a class="answer" href="" value="{{$answer->correct}}">{{$answer->answer}}</a>
                                @endforeach
                            @elseif ($currQues->type_id == 3)
                                @foreach ($currQuesAns as $answer)
                                    <div class="ansCard">
                                        <a class="cardLink" href="" value="{{$answer->correct}}">
                                        <img src="{{asset('/images/blank-card.png')}}" style="width:100px">{{$answer->answer}}</a>
                                    </div>
                                @endforeach
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
            })
        </script>

        @if($currQues->type_id == 3)
        <!--Script for card flipping questions-->
        <script>
        $(document).ready(function(){
            //COUNTDOWN TIMER
            var timeleft = 5;
            var timer = setInterval(function(){
            if(timeleft <= 0){
                clearInterval(timer);
                //window.location.href="{{route('managequiz')}}";
            } else {
                document.getElementById("countdown").innerHTML = timeleft + " seconds";
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
        @if($currQues->type_id == 4)
        <!--Script for quickfire questions-->
        <script>
        $(document).ready(function(){
            //COUNTDOWN TIMER
            var timeleft = 5;
            var timer = setInterval(function(){
            if(timeleft <= 0){
                clearInterval(timer);
            } else {
                document.getElementById("countdown").innerHTML = timeleft + " seconds";
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