@include('reportssidebar')
        <div class="report-container" align="center">
            <div class="report-header" id="header">
                <!-- Show Individual Subject Performance if Student, show All Students and Own Groups if Educator -->
                @if(Auth::user()->role == 0)
                    Individual Subject Performance</div><br>
                @else 
                    Subject Performance for All Students/Group Members</div><br>
                @endif
                <!-- Display Subjects and Groups toggle if educator, otherwise Maths and Science toggles only -->
                @if(Auth::user()->role == 1)
                    <a class="btn btn-primary" href="{{route('reports-view', 0)}}">Subjects</a>    
                    <a class="btn btn-primary" href="{{route('reports-view', 1)}}">Group</a>
                    <br><br>
                @endif
                @if($reportState == 0)
                    <button class="btn btn-primary" id="load-maths">Maths</button>
                    <button class="btn btn-primary" id="load-science">Science</button>
                    <br><br>
                    <div class="chart" id="quiz-barchart"></div>
                    <div class="chart" id="quiz-linechart"></div>
                    <div class="chart" id="quiz-piechart"></div>
                @else
                    <!-- If Groups toggle is used, display table of groups made by educator -->
                    @if(!empty($groups))
                        <table class='quiz-table' display="hidden">
                            <tr>
                                <th>Group Name</th>
                                <th>Group Description</th>
                                <th>Subject</th>
                                <th>View Chart</th>
                            </tr>
                            @foreach($groups as $group)
                            <tr>
                                <td>{{$group->group_name}}</td>
                                <td>{{$group->group_desc}}</td>
                                <td>{{$group->subject_name}}</td>
                                <td><a href="{{route('group-charts-view', $group->group_id )}}"><img src="{{asset('/images/chart.png')}}" style="width:20px"></a></td>
                            </tr>
                            @endforeach
                        </table>
                    <!-- If no groups found -->
                    @else
                        <p>You have no groups.</p>
                    @endif
                @endif
            <br>
        </div> 
    </body>
    <!--Script to change button visibility-->
    <script type="text/javascript">
        document.getElementById('change-subject').onclick = function() {
            document.getElementById('load-maths').style.visibility = "visible";
            document.getElementById('load-science').style.visibility = "visible";
            document.getElementById('quiz-barchart').style.visibility = "visible";
            document.getElementById('quiz-linechart').style.visibility = "visible";
            document.getElementById('quiz-piechart').style.visibility = "visible";
        }
        document.getElementById('change-group').onclick = function() {
            document.getElementById('load-maths').style.visibility = "hidden";
            document.getElementById('load-science').style.visibility = "hidden";
            document.getElementById('quiz-barchart').style.visibility = "hidden";
            document.getElementById('quiz-linechart').style.visibility = "hidden";
            document.getElementById('quiz-piechart').style.visibility = "hidden";
        }
    </script>

    <!--Script to load bar chart-->
    <script type="text/javascript">
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-maths').on('click', loadAll);
        $('#load-science').on('click', loadAll);
        $('#change-subject').on('click', loadAll);
        loadAll();
        });
        google.charts.setOnLoadCallback(loadAll);

        function loadAll() {
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
                    echo 'title: "Number of Quiz Attempts Done per Subject",';
                    }else {
                    echo 'title: "Number of Quiz Attempts Done per Subject for All Students",';
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

    <!--Script to load line chart-->
    <script type="text/javascript">
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience);
        $('#load-maths').on('click', loadMaths);
        $('#change-subject').on('click', loadMaths);
        loadMaths();
        });
        google.charts.setOnLoadCallback(loadMaths);
        function loadMaths() {
            var count = <?=$mathCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                <?php for($i = count($mathHistory)-1; $i >= 0; $i--) {
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
                    echo 'title: "Math Quizzes Performance Over 10 Attempts",';
                    }else {
                    echo 'title: "Math Quizzes Performance for All Students Over 10 Attempts",';
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
                ['Attempt', 'Score'],
                <?php for($i = count($sciHistory)-1; $i >= 0; $i--) {
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
                    echo 'title: "Science Quizzes Performance Over 10 Attempts",';
                    }else {
                    echo 'title: "Science Quizzes Performance for All Students Over 10 Attempts",';
                }?>
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }
    </script>

    <!--Script to load pie chart-->
    <script type="text/javascript">
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience2);
        $('#load-maths').on('click', loadMaths2);
        $('#change-subject').on('click', loadMaths2);
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
                    echo 'title: "Math Quizzes Question Accuracy",';
                    }else {
                    echo 'title: "Math Quizzes Question Accuracy for All Students",';
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
                    echo 'title: "Science Quizzes Question Accuracy",';
                    }else {
                    echo 'title: "Science Quizzes Question Accuracy for All Students",';
                }?>
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }
    </script>
</html>