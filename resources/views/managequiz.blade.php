        @include('header')
        <br>
        <div class="row justify-content-center">
            <div class="display-table">
                <!-- Check if quiz table is empty -->
                @if(!empty($quiz))
                <table class='quiz-table'>
                    <!-- First row -->
                    <tr>
                        <th>Quiz</th>
                        <th>Subject</th>
                        <th>Summary</th>
                        <th>Game Mode</th>
                        <th>Time (s)</th>
                        <th>Items Allowed?</th>
                        <th>Private?</th>
                        @if (Auth::user()->role == 0)
                            <th>Play</th>
                        @else
                            <th>Edit</th>
                            <th>Delete</th>
                        @endif
                    </tr>
                    @foreach($quiz as $quizView)
                        <tr id = "{{ $quizView -> quiz_id}}Row">
                            <td>{{$quizView -> quiz_title}} </td>
                            <td>{{$quizView -> subject_name}} </td>
                            <td>{{$quizView -> quiz_summary}} </td>
                            <td>{{$quizView -> gamemode_name}} </td>
                            <td>{{$quizView -> time_limit}} </td>
                            <!-- If items are not allowed, display no, otherwise yes -->
                            @if($quizView -> items == 0)
                                <td>No</td>
                            @else
                                <td>Yes</td>
                            @endif
                            <!-- Likewise, if group_id is null, display no, otherwise yes-->
                            @if($quizView -> group_id)
                                <td>Yes</td>
                            @else
                                <td>No</td>
                            @endif
                            @if (Auth::user()->role == 0)
                                <td><a href="">Attempt Quiz</td>
                            @else
                                <td><a href="{{route('createquiz2')}}">Edit</td>
                                <td><a href="">Delete</td>
                            @endif
                        </tr>
                    @endforeach
                </table>
                <!-- If no quiz available -->
                @else
                <p>No quizzes available</p>
                @endif
            </div>
            
        </div>
        <div class="row justify-content-center">
        @if (Auth::user()->role == 1)
            <form action="{{route('createquiz1')}}">
                <button type="submit" class="btn btn-primary">
                    Create a New Quiz
                </button>
            </form>
        @endif
        </div>
    </body>
</html>