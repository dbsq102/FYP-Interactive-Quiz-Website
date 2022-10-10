@include('header')
        <br>
        <div class="report-container" align="center">
            <br>
            <h1>Reports for {{Auth::user()->username}}</h1><br>
            @if(Auth::user()->role == 0)
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
            @endif

            <h3>Individual / Subject Performance</h3>
            <button class="btn btn-primary" id="load-maths">Maths</button>
            <button class="btn btn-primary" id="load-science">Science</button><br>
            <div class="chart" id="quiz-barchart"></div>
            <div class="chart" id="quiz-linechart"></div>
            <div class="chart" id="quiz-piechart"></div>
        </div> 
    </body>
    @if (Auth::user()->role == 0)
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
                title: "Number of Quizzes done Per Subject",
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var chart = new google.visualization.BarChart(document.getElementById("quiz-barchart"));
            chart.draw(view, options);
        }
    </script>

    <script>
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience);
        $('#load-maths').on('click', loadMaths);
        loadMaths();
        });
        google.charts.setOnLoadCallback(loadMaths);
        function loadMaths() {
            var data = google.visualization.arrayToDataTable([
            ['Attempt', 'Score'],
            ['<?= $history[0]->quiz_title ?>', <?= $history[0]->score ?>],
            ['<?= $history[1]->quiz_title ?>', <?= $history[1]->score ?>],
            ['<?= $history[2]->quiz_title ?>', <?= $history[2]->score ?>],
            ['<?= $history[3]->quiz_title ?>', <?= $history[3]->score ?>]
            ]);

            var options = {
            title: 'Maths Quiz Performance',
            legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }

        function loadScience() {
            var data = google.visualization.arrayToDataTable([
            ['Attempt', 'Score'],
            ['<?= $history[0]->quiz_title ?>', <?= $history[0]->score ?>],
            ['<?= $history[1]->quiz_title ?>', <?= $history[1]->score ?>],
            ['<?= $history[2]->quiz_title ?>', <?= $history[2]->score ?>],
            ['<?= $history[3]->quiz_title ?>', <?= $history[3]->score ?>]
            ]);

            var options = {
            title: 'Science Quiz Performance',
            legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }
    </script>
    <script>
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience2);
        $('#load-maths').on('click', loadMaths2);
        loadMaths();
        });
        google.charts.setOnLoadCallback(loadMaths2);
        function loadMaths2() {
            var data = google.visualization.arrayToDataTable([
            ['Correct', 'Questions'],
            ['Correct', <?= $sumMathScore ?>],
            ['Incorrect', <?= $countQuesMath - $sumMathScore ?>]
            ]);

            var options = {
            title: 'Maths Quizzes Accuracy'
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }

        function loadScience2() {
            var data = google.visualization.arrayToDataTable([
            ['Correct', 'Questions'],
            ['Correct', <?= $sumScienceScore ?>],
            ['Incorrect', <?= $countQuesSci - $sumScienceScore ?>]
            ]);

            var options = {
            title: 'Science Quizzes Accuracy'
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }
    </script>
    @else
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Subject", "No. of Quizzes", { role: "style" } ],
                ["Science", 10, "green"],
                ["Math", 14, "blue"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);

            var options = {
                title: "Number of Quizzes done Per Subject across all students",
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var chart = new google.visualization.BarChart(document.getElementById("quiz-barchart"));
            chart.draw(view, options);
        }
    </script>

    <script>
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience);
        $('#load-maths').on('click', loadMaths);
        loadMaths();
        });
        google.charts.setOnLoadCallback(loadMaths);
        function loadMaths() {
            var data = google.visualization.arrayToDataTable([
            ['Attempt', 'Score'],
            ['Quiz 1', 3],
            ['Quiz 2', 2],
            ['Quiz 3', 3],
            ['Quiz 4', 1]
            ]);

            var options = {
            title: 'Maths Quiz Performance',
            legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }

        function loadScience() {
            var data = google.visualization.arrayToDataTable([
            ['Attempt', 'Score'],
            [<?= $history[0]?>, 5],
            ['Quiz 2', 4],
            ['Quiz 3', 1],
            ['Quiz 4', 3]
            ]);

            var options = {
            title: 'Science Quiz Performance',
            legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }
    </script>
    <script>
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-science').on('click', loadScience2);
        $('#load-maths').on('click', loadMaths2);
        loadMaths();
        });
        google.charts.setOnLoadCallback(loadMaths2);
        function loadMaths2() {
            var data = google.visualization.arrayToDataTable([
            ['Task', 'Questions'],
            ['Correct', <?= $sumMathScore ?>],
            ['Incorrect', <?= $countQuesMath - $sumMathScore?>]
            ]);

            var options = {
            title: 'Maths Quizzes Accuracy for All Students'
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }

        function loadScience2() {
            var data = google.visualization.arrayToDataTable([
            ['Task', 'Questions'],
            ['Correct', <?= $sumScienceScore ?>],
            ['Incorrect', <?= $countQuesSci - $sumScienceScore ?>]
            ]);

            var options = {
            title: 'Science Quizzes Accuracy for All Students'
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }
    </script>
    @endif
</html>