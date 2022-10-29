@include('header')
        <br>
        <div class="report2-container" align="center">
            <!-- Charts with Group Statistics -->
            <div class="report-header">Group Charts: {{$getGroupName}}</div><br>
            <div class="chart" id="quiz-barchart"></div><br>
            <div class="chart" id="quiz-linechart"></div><br>
            <div class="chart" id="quiz-piechart"></div><br>
            <!-- Button to return to prior page -->
            <a class="btn btn-primary" href="{{route('reports-view', 0)}}">Go Back</a><br><br>
        </div>
    </body>
    <!-- Script for group barchart -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(loadGroup);
        
        function loadGroup() {
            var data = google.visualization.arrayToDataTable([
                ["Group Member", "No. of Quizzes", { role: "style" } ],
                ["No. of Group Members", <?= $cntGroupMem ?>, "red"],
                ["No. of Group Members that Attempted a Quiz", <?= $cntGroupMemAttempts ?>, "green"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);

            var options = {
                title: "Participation Rate of Group Members in Assigned Quizzes",
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var chart = new google.visualization.BarChart(document.getElementById("quiz-barchart"));
            chart.draw(view, options);
        }
    </script>
    <!-- Script for group linechart -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(loadGroup1);

        function loadGroup1() {
            var count = <?=$groupCountAttempt?>;
            if (count > 0) {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                <?php for($i = $cntGroupMem-1; $i >= 0; $i--) {
                    echo '["'.$groupMember[$i]->username.'",'.$avgScore[$i].'],';
                }?>
                ]);
            } else {
                var data = google.visualization.arrayToDataTable([
                ['Attempt', 'Score'],
                ['None', 0]
            ]);

            }

            var options = {
                title: "Average Quiz Performance for Group Members",
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('quiz-linechart'));

            chart.draw(data, options);
        }
    </script>
    <!-- Script for group pie chart -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(loadGroup2);

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
                title: "Question Accuracy for Group Members in Assigned Quizzes",
            };

            var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

            chart.draw(data, options);
        } 
    </script>
</html>