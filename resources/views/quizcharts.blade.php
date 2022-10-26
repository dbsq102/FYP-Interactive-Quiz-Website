@include('header')
        <br>
        <div class="report2-container" align="center">
            <div class="report-header">{{$quiz->username}}'s Attempt of {{$quiz->quiz_title}}</div><br>
            <div class="chart" id="quiz-piechart"></div><br>
            <a class="btn btn-primary" href="{{route('reports-view', 0)}}">Go Back</a><br><br>
        </div>
    </body>
    <!--script for piechart of correct/incorrect questions-->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Correct', 'Number'],
          ['Correct', <?= $quiz->score?>],
          ['Incorrect', <?= $countQues - $quiz->score?>]
        ]);

        var options = {
          title: 'Quiz Question Accuracy'
        };

        var chart = new google.visualization.PieChart(document.getElementById('quiz-piechart'));

        chart.draw(data, options);
      }
    </script>
</html>