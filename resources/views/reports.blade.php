@include('header')
        <br>
        <div class="report-container" align="center">
            <br>
            <button class="btn btn-primary" id="load-math">Maths</button>
            <button class="btn btn-primary" id="load-science">Science</button>
            <button class="btn btn-primary" id="load-english">English</button>
            <button class="btn btn-primary" id="load-morals">Morals</button>
            <div class="student-subject-quiz-bar-chart" id="quiz-barchart" style="width: 900px; height: 500px;"></div>
            <div class="student-accuracy-piechart1" id="quiz-piechart1" style="width: 900px; height: 500px;"></div>
        </div> 
    </body>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Subject", "No. of Quizzes", { role: "style" } ],
                ["Science", 1, "green"],
                ["Math", 2, "blue"],
                ["English", 0, "red"],
                ["Morals", 1, "yellow"]
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

</html>