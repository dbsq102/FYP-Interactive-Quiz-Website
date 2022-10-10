@include('header')
        <br>
        <div class="group-container" align="center">
            @if(!empty($userGroup))
            <div class ="group-header">Your Group: {{$userGroup->group_name}} </div>
                <div class="group-content">
                    <div class="group-desc">
                        <h3>Group Description:</br>{{$userGroup->group_desc}}</h3>
                    </div>
                    <div class="group-subject">
                        <h3>Group Subject: <br>{{$userGroup->subject_name}}</h3>
                    </div>
                    <div class="group-members">
                        <h3>Group Members:</h3>
                        <table class='quiz-table'>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                @if(Auth::user()->role == 1) 
                                    <th>Remove Student</th>
                                @endif
                            </tr>
                            @foreach($users as $groupMembers)
                                <tr>
                                    <td style="font-weight:bold;">{{$groupMembers->username}}</td>
                                    <td>{{$groupMembers->email}}</td>
                                    @if($groupMembers->role == 0)
                                        <td>Student</td>
                                    @else
                                        <td>Instructor</td>
                                    @endif
                                    @if(Auth::user()->role == 1) 
                                        @if($groupMembers->role == 0)
                                            <td><a onclick="return confirm('Are you sure you want to remove this student from the group?')"href="{{route('leave-group', $groupMembers->user_id )}}">
                                            <img src="{{asset('/images/delete.png')}}" style="width:20px"></a></td>
                                        @else
                                            <td>Cannot Remove</td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                        @if (Auth::user()->role == 1) 
                        <br>
                        <h3>Add a student to the group:</h3>
                            <form method="POST" action="{{route('add-to-group', $userGroup->group_id )}}">
                                @csrf
                                <div class="add-student">
                                    <select name="user_id" id="user_id" required>
                                        <option value="">Student Name</option>
                                        @foreach($noGroup as $student)
                                            <option value="{{$student->user_id}}">{{$student->username}}</option>
                                        @endforeach
                                    </select>
                                </div><br>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Student') }}
                                </button>
                            </form>
                        @endif
                    </div><br>
                    <h3>Recent Quizzes assigned to {{$userGroup->group_name}}</h3>
                    <div class="recentQuiz">
                        <table class='quiz-table'>
                            <tr><th colspan='7'>Recent Quizzes Assigned to {{$userGroup->group_name}}<th></tr>
                            @if(!$quiz->isEmpty())
                                <!-- First row -->
                                <tr>
                                    <th>Quiz</th>
                                    <th>Subject</th>
                                    <th>Summary</th>
                                    <th>Game Mode</th>
                                    <th>Time (m)</th>
                                    <th>Items Allowed?</th>
                                    <th>No. of Questions</th>
                                    @if (Auth::user()->role == 0)
                                        <th>Play</th>
                                    @else
                                        <th>Edit</th>
                                    @endif
                                </tr>
                                @foreach($quiz as $quizView)
                                <tr id = "{{ $quizView -> quiz_id}}Row">
                                    <td style="font-weight:900;">{{$quizView -> quiz_title}} </td>
                                    <td>{{$quizView -> subject_name}} </td>
                                    <td>{{$quizView -> quiz_summary}} </td>
                                    <td>{{$quizView -> gamemode_name}} </td>
                                    @if($quizView -> gamemode_id == 2 || $quizView -> gamemode_id == 3)
                                        <td>None</td>
                                    @else
                                        <td>{{$quizView -> time_limit}} </td>
                                    @endif
                                    <!-- If items are not allowed, display no, otherwise yes -->
                                    @if($quizView -> items == 0)
                                        <td>No</td>
                                    @else
                                        <td>Yes</td>
                                    @endif
                                    <!--Count number of questions on the quiz-->
                                    <td>{{App\Models\Question::where('quiz_id', '=', $quizView->quiz_id)->count();}}</td>
                                    @if (Auth::user()->role == 0)
                                        @if(!$quizView -> group_id || $quizView ->group_id == Auth::user()->group_id)
                                            <td><a class="link" href="{{route('standby', $quizView->quiz_id)}}"><img src="{{asset('/images/play.png')}}" style="width:20px"></td>
                                        @else
                                            <td>Private</td>
                                        @endif      
                                    @else
                                        @if(!$quizView -> group_id || $quizView ->group_id == Auth::user()->group_id)
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
                                @endforeach
                            @else
                                <tr><td colspan='7'>No quizzes assigned to this group</td></tr>
                            @endif
                        </table>
                    </div>
                    @if (Auth::user()->role == 0)
                        <a class="btn btn-primary" onclick="return confirm('Are you sure you want to leave this group?')" href="{{route('leave-group', Auth::id() )}}">Leave Group</a>
                    @else
                        <a class="btn btn-primary" onclick="return confirm('Are you sure you want to remove this group?')" href="{{route('delete-group', $userGroup->group_id) }}">Remove Group</a>
                    @endif
                </div>
            </div>
            @else
                <div class ="group-header">Unfortunately, you have no group.</div>
                <div class="group-content">
                    @if(Auth::user()->role == 0)
                        <p>You may join a group here.</p>
                        <table class='quiz-table'>
                            <tr>
                                <th>Group Name</th>
                                <th>Group Description</th>
                                <th>Subject</th>
                                <th>Join</th>
                            </tr>
                            @foreach($allGroups as $publicGroups)
                            <tr>
                                <td>{{$publicGroups->group_name}}</td>
                                <td>{{$publicGroups->group_desc}}</td>
                                <td>{{$publicGroups->subject_name}}</td>
                                <td><a href="{{route('join-group', $publicGroups->group_id )}}">Join</a>
                            </tr>
                            @endforeach
                        </table>
                    @elseif(Auth::user()->role == 1)
                        <a class="btn btn-primary" href="{{route('create-group-view')}}">Create a new Group</a>
                    @endif
                </div>
            @endif
        </div>
    </body>
<html>