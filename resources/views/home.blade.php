        @include('header')
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!-- Shows create quiz shortcut if educator, available quizzes in group if student -->
                    <div class="card-header">
                        Welcome, {{Auth::user()->username }}.
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (Auth::user()->role == 0)
                            @if($getGroup)
                                <p>Here are some quizzes assigned to you by your groups. </p>
                                <!-- put display quiz code here -->
                                @if(!empty($quiz))
                                    <div class="recentQuiz">
                                        <table class='quiz-table'>
                                            <tr>
                                                <th>Group</th>
                                                <th>Quiz Title</th>
                                                <th>Subject</th>
                                                <th>Quiz Summary</th>
                                                <th>Quiz Type</th>
                                                <th>Play</th>
                                            </tr>
                                            @foreach($quiz as $quizView)
                                                @if($quizView->group_id == (App\Models\Member::where('user_id','=',Auth::id())->where('group_id', '=', $quizView->group_id)->value('group_id')) )
                                                    <tr id = "{{ $quizView -> quiz_id}}Row">
                                                        <td>{{$quizView -> group_name}} </td>
                                                        <td>{{$quizView -> quiz_title}} </td>
                                                        <td>{{$quizView -> subject_name}} </td>
                                                        <td>{{$quizView -> quiz_summary}} </td>
                                                        <td>{{$quizView -> gamemode_name}} </td>
                                                        @if (Auth::user()->role == 0)
                                                            @if(!$quizView -> group_id || $quizView ->group_id == (App\Models\Member::where('user_id','=',Auth::id())->where('group_id', '=', $quizView->group_id)->value('group_id')))
                                                                @if($completeCheck[$loop->iteration-1] == 1)
                                                                    <td><a class="link" href="{{route('standby', $quizView->quiz_id)}}"><img src="{{asset('/images/play.png')}}" style="width:20px"></td>
                                                                @else
                                                                    <td>Quiz is not complete</td>
                                                                @endif
                                                            @else
                                                                <td>Private</td>
                                                            @endif      
                                                        @else
                                                            @if(!$quizView -> group_id || $quizView ->group_id == (App\Models\Member::where('user_id','=',Auth::id())->where('group_id', '=', $quizView->group_id)->value('group_id')) )
                                                                @if($quizView->user_id == Auth::id())
                                                                    <td><a class="link" href= "{{route('editquiz', $quizView->quiz_id ) }}"><img src="{{asset('/images/edit.png')}}" style="width:20px"></td>
                                                                @else
                                                                    <td>Only Creator can Edit</td>
                                                                @endif
                                                            @else
                                                                <td>Cannot Edit</td>
                                                            @endif
                                                        @endif
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    </div>
                                <!-- If no quiz available -->
                                @else
                                <p>No quizzes available</p>
                                @endif
                            @else
                            <p>You have no group, unfortunately.</p>
                            @endif
                        @else
                            <p>Let's make a new quiz for students! </p>
                            <form action="{{route('createquiz')}}">
                                <button type="submit" class="btn btn-primary">
                                    Create a New Quiz
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">Recently Available Quizzes</div>
                    @if(!empty($quiz2))
                    <div class="recentQuiz">
                        <table class='quiz-table'>
                            <tr>
                                <th>Quiz Title</th>
                                <th>Subject</th>
                                <th>Quiz Summary</th>
                                <th>Quiz Type</th>
                                @if(Auth::user()->role == 0)
                                    <th>Play</th>
                                @else
                                    <th>Edit</th>
                                @endif
                            </tr>
                            @foreach($quiz2 as $quizView)
                                @if($completeCheck2[$loop->iteration-1] == 1)
                                    <tr id = "{{ $quizView -> quiz_id}}Row">
                                        <td>{{$quizView -> quiz_title}} </td>
                                        <td>{{$quizView -> subject_name}} </td>
                                        <td>{{$quizView -> quiz_summary}} </td>
                                        <td>{{$quizView -> gamemode_name}} </td>
                                        @if (Auth::user()->role == 0)
                                            <td><a class="link" href="{{route('standby', $quizView->quiz_id)}}"><img src="{{asset('/images/play.png')}}" style="width:20px"></td>
                                        @else
                                            @if($quizView->user_id == Auth::id())
                                                <td><a class="link" href= "{{route('editquiz', $quizView->quiz_id ) }}"><img src="{{asset('/images/edit.png')}}" style="width:20px"></td>
                                            @else
                                                <td>Only Creator can Edit</td>
                                            @endif
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    <!-- If no quiz available -->
                    @else
                    <p>No quizzes available</p>
                    @endif
                </div>
                <br>
            </div>
        </div>
    </body>
</html>
