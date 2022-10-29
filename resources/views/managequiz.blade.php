        @include('header')
        <br>
        <div align="center">
            <h2>List of available quizzes</h2>
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
                        <th>Time (m)</th>
                        <th>No. of Questions</th>
                        <!-- Different displays depending on role -->
                        @if (Auth::user()->role == 0)
                            <th>Play</th>
                        @else
                            <th>Public?</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        @endif
                    </tr>
                    @foreach($quiz as $quizView)
                        @if($completeCheck[$loop->iteration-1] == 1 || Auth::user()->role == 1)
                        <tr id = "{{ $quizView -> quiz_id}}Row">
                            <td>{{$quizView -> quiz_title}} </td>
                            <td>{{$quizView -> subject_name}} </td>
                            <td>{{$quizView -> quiz_summary}} </td>
                            <td>{{$quizView -> gamemode_name}} </td>
                            @if($quizView -> gamemode_id == 2 || $quizView -> gamemode_id == 3)
                                <td>None</td>
                            @else
                                <td>{{$quizView -> time_limit}} </td>
                            @endif
                            <!-- Count number of questions on the quiz -->
                            <td>{{App\Models\Question::where('quiz_id', '=', $quizView->quiz_id)->count();}}</td>
                            <!-- Likewise, if group_id is null, display no, otherwise yes-->
                            @if (Auth::user()->role == 1)
                                @if(!$quizView -> group_id)
                                    <td>Open</td>
                                @else
                                    <td>Private</td>
                                @endif
                            @endif
                            <!-- If user is student, allow to take the quiz -->
                            @if (Auth::user()->role == 0)
                                @if(!$quizView -> group_id || $quizView ->group_id == (App\Models\Member::where('user_id','=',Auth::id())->where('group_id', '=', $quizView->group_id)->value('group_id')))
                                    <td><a class="link" href="{{route('standby', $quizView->quiz_id)}}"><img src="{{asset('/images/play.png')}}" style="width:20px"></td>
                                @else
                                    <td>Private</td>
                                @endif
                            <!-- Otherwise, grant buttons to edit or delete the quiz -->
                            @else
                                @if(!$quizView -> group_id || $quizView ->group_id == (App\Models\Member::where('user_id','=',Auth::id())->where('group_id', '=', $quizView->group_id)->value('group_id')))
                                    @if($quizView->user_id == Auth::id())
                                        <td><a class="link" href= "{{route('editquiz', $quizView->quiz_id ) }}"><img src="{{asset('/images/edit.png')}}" style="width:20px"></td>
                                        <td><a class="link" onclick="return confirm('Are you sure you want to delete this quiz?')"href="{{route('delete-quiz', $quizView->quiz_id) }}"><img src="{{asset('/images/delete.png')}}" style="width:20px"></td>
                                    @else
                                        <td>Only Creator can Edit</td>
                                        <td>Only Creator can Delete </td>
                                    @endif
                                @else
                                    <td>Cannot Edit</td>
                                    <td>Cannot Delete </td>
                                @endif
                            @endif
                        </tr>
                        @endif
                    @endforeach
                </table>
                <!-- If no quiz available -->
                @else
                <p>No quizzes available</p>
                @endif
            </div>
            
        </div><br>
        <div class="row justify-content-center">
        <!-- Create Quiz Button-->
        @if (Auth::user()->role == 1)
            <form action="{{route('createquiz')}}">
                <button type="submit" class="btn btn-primary">
                    Create a New Quiz
                </button>
            </form>
        @endif
        </div>
    </body>
</html>