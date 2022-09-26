        @include('header')
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!-- Shows create quiz shortcut if educator, available quizzes in group if student -->
                    <!-- Student view -->
                    <div class="card-header">
                        Welcome, {{Auth::user()->username }}
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (Auth::user()->role == 0)
                            <p>Here are some quizzes assigned to your group, [GroupName]. </p>
                            <!-- put display quiz code here -->
                            <p>No quizzes for your group.</p>
                        @else
                            <p>Let's make a new quiz for students! </p>
                            <form action="{{route('createquiz1')}}">
                                <button type="submit" class="btn btn-primary">
                                    Create a New Quiz
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <br>
                <!-- Educator view -->
                <div class="card">
                    <div class="card-header">
                        Available Quizzes
                    </div>
                    <div class="card-body">
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
                                        @if (Auth::user()->role == 0)
                                            <th>Play</th>
                                        @else
                                            <th>Public?</th>
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
                                            @if (Auth::user()->role == 1)
                                                @if(!$quizView -> group_id)
                                                    <td>Open</td>
                                                @else
                                                    <td>Private</td>
                                                @endif
                                            @endif
                                            @if (Auth::user()->role == 0)
                                                @if(!$quizView -> group_id || $quizView ->group_id == Auth::user()->group_id)
                                                    <td><a href="">Attempt Quiz</td>
                                                @else
                                                    <td>Private</td>
                                                @endif
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
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
