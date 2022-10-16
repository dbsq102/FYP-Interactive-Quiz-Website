@include('header')
        <br>
        <div class="report-container" align="center">
            <div class="report-header">Reports</div><br>
            @if(Auth::user()->role == 0)
                <h2>Reports for {{Auth::user()->username}}</h2><br>
                <!-- Display all quiz attempts from user -->
                <table class="quiz-table">
                    <!-- First Row -->
                    <tr>
                        <th>Quiz Title</th>
                        <th>Date Taken</th>
                        <th>Score</th>
                        <th>Charts</th>
                    </tr>
                    @foreach ($history as $attempts)
                        <tr>
                            <td>{{$attempts->quiz_title}}</td>
                            <td>{{$attempts->date_taken}}</td>
                            <td>
                                <label for="score">{{$attempts->score}} / {{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}</label>
                                <progress id="score" value="{{$attempts->score}}" max="{{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}"></progress>
                            </td>
                            <td>
                                <a href="Chart Page"><img src="{{asset('/images/chart.png')}}" style="width:20px"></a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <br>
            @else
                <h2>All Reports for Quizzes made by {{Auth::user()->username}}</h2></br>
                <!-- Display all quiz attempts on quizzes made by user -->
                <table class="quiz-table">
                    <!-- First Row -->
                    <tr>
                        <th>Username</th>
                        <th>Quiz Title</th>
                        <th>Date Taken</th>
                        <th>Score</th>
                        <th>Charts</th>
                    </tr>
                    @foreach ($history as $attempts)
                        <tr>
                            <td>{{$attempts->username}}</td>
                            <td>{{$attempts->quiz_title}}</td>
                            <td>{{$attempts->date_taken}}</td>
                            <td>
                                <label for="score">{{$attempts->score}} / {{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}</label>
                                <progress id="score" value="{{$attempts->score}}" max="{{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}"></progress>
                            </td>
                            <td>
                                <a href="{{route('quiz-charts-view', $attempts->quiz_id) }}"><img src="{{asset('/images/chart.png')}}" style="width:20px"></a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <br>
            @endif
            @if(Auth::user()->role == 0)
                <h3>Individual / Subject Performance</h3>
            @else
                <h3>Subject Performance for All Students</h3>
            @endif
            <button class="btn btn-primary" id="load-maths">Maths</button>
            <button class="btn btn-primary" id="load-science">Science</button><br>
            <div class="chart" id="quiz-barchart"></div>
            <div class="chart" id="quiz-linechart"></div>
            <div class="chart" id="quiz-piechart"></div>
        </div> 
    </body>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Subject", "No. of Quizzes", { role: "style" } ],
                ["Science", <?= $countSci ?>, "green"],
                ["Math", <?= $countMath ?>, "blue"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);

            var options = {
                <?php if (Auth::user()->role == 0) {
                    echo 'title: "Number of Quizzes Done per Subject",';
                    }else {
                    echo 'title: "Number of Quizzes Done per Subject for All Students",';
                    }?>
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var chart = new google.visualization.BarChart(document.getElementById("quiz-barchart"));
            chart.draw(view, options);
        }
    </script>

    <script type="text/javascript">
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience);
        $('#load-maths').on('click', loadMaths);
        loadMaths();
        });
        google.charts.setOnLoadCallback(loadMaths);
        function loadMaths() {
            var count = <?=$mathCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                <?php for($i = 0; $i < count($mathHistory); $i++) {
                    echo '["'.$mathHistory[$i]->quiz_title.'",'.$mathHistory[$i]->score.'],';
                }?>
                ]);
            } else {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                ['None', 0]
            ]);

            }

            var options = {
                <?php if (Auth::user()->role == 0) {
                    echo 'title: "Math Quiz Performance",';
                    }else {
                    echo 'title: "Math Quiz Performance for All Students",';
                    }?>
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }

        function loadScience() {
            var count = <?=$sciCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                    <?php for($i = 0; $i < count($sciHistory); $i++) {
                    echo '["'.$sciHistory[$i]->quiz_title.'",'.$sciHistory[$i]->score.'],';
                }?>
                ]);
            } else {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                ['None', 0]
            ]);

            }

            var options = {
                <?php if (Auth::user()->role == 0) {
                    echo 'title: "Science Quiz Performance",';
                    }else {
                    echo 'title: "Science Quiz Performance for All Students",';
                }?>
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience2);
        $('#load-maths').on('click', loadMaths2);
        loadMaths2();
        });
        google.charts.setOnLoadCallback(loadMaths2);
        function loadMaths2() {
            var count = <?=$mathCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Correct', 'Questions'],
                ['Correct', <?= $sumMathScore ?>],
                ['Incorrect', <?= $countQuesMath - $sumMathScore ?>]
                ]);
            }else {
                var data = google.visualization.arrayToDataTable([
                ['Correct', 'Questions'],
                ['Correct', 1],
                ['Incorrect', 1]
                ]);
            }
            var options = {
                <?php if (Auth::user()->role == 0) {
                    echo 'title: "Math Quizzes Accuracy",';
                    }else {
                    echo 'title: "Math Quizzes Accuracy for All Students",';
                }?>
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }

        function loadScience2() {
            var count = <?=$sciCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Correct', 'Questions'],
                ['Correct', <?= $sumScienceScore ?>],
                ['Incorrect', <?= $countQuesSci - $sumScienceScore ?>]
                ]);
            }else {
                var data = google.visualization.arrayToDataTable([
                ['Correct', 'Questions'],
                ['Correct', 1],
                ['Incorrect', 1]
                ]);
            }
            var options = {
                <?php if (Auth::user()->role == 0) {
                    echo 'title: "Science Quizzes Accuracy",';
                    }else {
                    echo 'title: "Science Quizzes Accuracy for All Students",';
                }?>
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }
    </script>
</html>