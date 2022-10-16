@include('reportssidebar')
        <div class="report-container" align="center">
            <div class="report-header" id="header">
                @if(Auth::user()->role == 0)
                    Individual Quiz & Subject Performance</div><br>
                @else 
                    Quiz & Subject Performance for All Students/Group Members</div><br>
                @endif
            <button class="btn btn-primary" id="load-maths">Maths</button>
            <button class="btn btn-primary" id="load-science">Science</button>
            @if(Auth::user()->role == 0)
            <br><br>
            @else
            <button class="btn btn-primary" id="load-group">Group</button><br><br>
            @endif
            <div class="chart" id="quiz-barchart"></div>
            <div class="chart" id="quiz-linechart"></div>
            <div class="chart" id="quiz-piechart"></div>
        </div> 
    </body>
    
    <!--Script to load bar chart-->
    <script type="text/javascript">
        google.charts.load('current', {
        packages: ['corechart']
        }).then(function () {
        $('#load-maths').on('click', loadAll);
        $('#load-science').on('click', loadAll);
        $('#load-group').on('click', loadGroup);
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
        
        function loadGroup() {
            var data = google.visualization.arrayToDataTable([
                ["Group", "No. of Quizzes", { role: "style" } ],
                ["<?= $groupSubName ?>", <?= $countGroup ?>, "blue"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);

            var options = {
                title: "Number of Quizzes done by Students in Your Group",
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
        $('#load-group').on('click', loadGroup1);
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
                    echo 'title: "Science Quiz Performance",';
                    }else {
                    echo 'title: "Science Quiz Performance for All Students",';
                }?>
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }
        
        function loadGroup1() {
            var count = <?=$groupCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                <?php for($i = count($groupHistory)-1; $i >= 0; $i--) {
                    echo '["'.$groupHistory[$i]->quiz_title.'",'.$groupHistory[$i]->score.'],';
                }?>
                ]);
            } else {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                ['None', 0]
            ]);

            }

            var options = {
                title: "Quiz Performance for Students in Your Group",
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
        $('#load-group').on('click', loadGroup2);
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

        function loadGroup2() {
            var count = <?=$groupCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Correct', 'Questions'],
                ['Correct', <?= $sumGroupScore ?>],
                ['Incorrect', <?= $countGroupQues - $sumGroupScore ?>]
                ]);
            }else {
                var data = google.visualization.arrayToDataTable([
                ['Correct', 'Questions'],
                ['Correct', 1],
                ['Incorrect', 1]
                ]);
            }
            var options = {
                title: "Average Quizzes Accuracy for All Students in Group",
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        }
    </script>

    <!--Script to change Header-->
    <script>

    </script>
</html>