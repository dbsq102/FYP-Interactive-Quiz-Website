@include('header')
        <br>
        <div class="report-container" align="center">
            <div class="report-header">Charts for Attempt of {{$quiz->quiz_title}}</div><br>
            <div class="chart" id="quiz-linechart"></div>
            <div class="chart" id="quiz-piechart"></div><br>
            <a class="btn btn-primary" href="{{route('reports-view')}}">Go Back</a><br><br>
        </div>
    </body>
    <!--Script for linechart of question performance-->
    <script>
    </script>
    <!--script for piechart of correct/incorrect questions-->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Correct', 'Number'],
          ['Correct', <?= $quiz->score?>],
          ['Incorrect', 2]
        ]);

        var options = {
          title: 'Quiz Question Accuracy'
        };

        var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

        chart.draw(data, options);
      }
    </script>
</html>