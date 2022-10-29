        @include('header')
        <br>
        <div class="sidebar2">
            <!-- Sidebar for reports -->
            <div class="sidebar2-container">
                <div class="sidebar-header">
                    @if(Auth::user()->role == 0)
                        Past 10 Quiz Attempts for {{Auth::user()->username}}</div>
                    @else 
                        Past 10 Quiz Attempts for Quizzes made by {{Auth::user()->username}}</div>
                    @endif
                    <div class="sidebar2-content">
                        @if(Auth::user()->role == 0)
                            <!-- Display all quiz attempts from user -->
                            <table class="quiz-table">
                                <!-- First Row -->
                                <tr>
                                    <th>Quiz Title</th>
                                    <th>Date Taken</th>
                                    <th>Score</th>
                                    <th>Chart</th>
                                </tr>
                                @if (!$history->isEmpty())
                                @foreach ($history as $attempts)
                                    <tr>
                                        <td>{{$attempts->quiz_title}}</td>
                                        <td>{{$attempts->date_taken}}</td>
                                        <td>
                                            <label for="score">{{$attempts->score}} / {{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}} </label>
                                            <progress id="score" value="{{$attempts->score}}" max="{{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}"></progress>
                                        </td>
                                        <td>
                                            <a href="{{route ('quiz-charts-view', $attempts->history_id)}}"><img src="{{asset('/images/chart.png')}}" style="width:20px"></a>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">You have not taken any quiz attempts.</td>
                                    </tr>
                                @endif
                            </table>
                            <br>
                        @else
                        <table class="quiz-table">
                            <!-- First Row -->
                            <tr>
                                <th>Username</th>
                                <th>Quiz Title</th>
                                <th>Date Taken</th>
                                <th>Score</th>
                                <th>Chart</th>
                            </tr>
                            @if (!$history->isEmpty())
                            @foreach ($history as $attempts)
                                <tr>
                                    <td>{{$attempts->username}}</td>
                                    <td>{{$attempts->quiz_title}}</td>
                                    <td>{{$attempts->date_taken}}</td>
                                    <td>
                                        <label for="score">{{$attempts->score}} / {{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}</label>
                                        <progress id="score" value="{{$attempts->score}}" max="{{App\Models\Question::where('quiz_id', '=', $attempts->quiz_id)->count();}}"></progress>
                                    </td>
                                    <td>
                                        <a href="{{route('quiz-charts-view', $attempts->history_id) }}"><img src="{{asset('/images/chart.png')}}" style="width:20px"></a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="5">There are no quiz attempts for quizzes created by you.</td>
                                </tr>
                            @endif
                        </table>
                        <br>
                    @endif
                </div>
            </div>
        </div>