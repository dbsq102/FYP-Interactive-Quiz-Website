@include('groupssidebar')
        <div class="group-container" align="center">
            <!-- Group information -->
            @if(!empty($userGroup))
            <div class ="group-header">Group: {{$userGroup->group_name}} </div>
                <div class="group-content">
                    <div class="group-desc">
                        <h4>Group Description:</h4>
                        <p>{{$userGroup->group_desc}}</p>
                    </div>
                    <div class="group-subject">
                        <h4>Group Subject: </h4>
                        <p>{{$userGroup->subject_name}}</p>
                    </div>
                    <!-- Group members, educator can remove members if they are the original creator -->
                    <div class="group-members">
                        <h4>Group Members:</h4>
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
                                    @if(Auth::user()->role == 1 && $userGroup->user_id == Auth::id()) 
                                        @if($groupMembers->role == 0)
                                            <td><a onclick="return confirm('Are you sure you want to remove this student from the group?')"href="{{route('kick-group', $groupMembers->user_id)}}">
                                            <img src="{{asset('/images/delete.png')}}" style="width:20px"></a></td>
                                        @else
                                            <td>Cannot Remove</td>
                                        @endif
                                    @else
                                        @if(Auth::user()->role == 1)
                                            <td>Only original creator can remove</td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                        <!-- If the user is an educator and the original creator, they can manually add students to the group -->
                        @if (Auth::user()->role == 1 && $userGroup->user_id == Auth::id()) 
                        <br>
                        <h4>Add a student to the group:</h4>
                            <form method="POST" action="{{route('add-to-group', $userGroup->group_id )}}">
                                @csrf
                                <div class="add-student">
                                    <select name="user_id" id="user_id" required>
                                        <option value="">Student Name</option>
                                        @foreach($students as $student)
                                            @if(App\Models\Member::where('user_id','=',$student->user_id)->where('group_id','=',$userGroup->group_id)->count() == 0)
                                                <option value="{{$student->user_id}}">{{$student->username}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div><br>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Student') }}
                                </button>
                            </form>
                        @endif
                    </div><br>
                    <!-- Display recently assigned quizzes -->
                    <h4>Recent Quizzes assigned to {{$userGroup->group_name}}</h4>
                    <div class="recentQuiz">
                        <table class='quiz-table'>
                            <tr><th colspan='6'>Recent Quizzes Assigned to {{$userGroup->group_name}}<th></tr>
                            @if(!$quiz->isEmpty())
                                <!-- First row -->
                                <tr>
                                    <th>Quiz</th>
                                    <th>Subject</th>
                                    <th>Summary</th>
                                    <th>Game Mode</th>
                                    <th>Time (m)</th>
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
                                    <!-- Count number of questions on the quiz -->
                                    <td>{{App\Models\Question::where('quiz_id', '=', $quizView->quiz_id)->count();}}</td>
                                    <!-- If student, allow to take quiz. If educator, allow to edit quiz -->
                                    @if (Auth::user()->role == 0)
                                        <td><a class="link" href="{{route('standby', $quizView->quiz_id)}}"><img src="{{asset('/images/play.png')}}" style="width:20px"></td>
                                    @else
                                        @if(!$quizView -> group_id || $quizView ->group_id == (App\Models\Member::where('user_id','=',Auth::id())->value('group_id')))
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
                                <tr><td colspan='6'>No quizzes assigned to this group</td></tr>
                            @endif
                        </table>
                    </div>
                    <!-- If student, allow to leave the group. If educator, allow to remove the group -->
                    @if (Auth::user()->role == 0)
                        <a class="btn btn-primary" onclick="return confirm('Are you sure you want to leave this group?')" href="{{route('leave-group', $userGroup->group_id )}}">Leave Group</a>
                    @else
                        @if(Auth::user()->role == 1 && $userGroup->user_id == Auth::id()) 
                            <a class="btn btn-primary" onclick="return confirm('Are you sure you want to remove this group?\nThis will remove all students in the group.')" href="{{route('delete-group', $userGroup->group_id) }}">Remove Group</a>
                        @endif
                    @endif
                </div>
            </div>
            <!-- Default display upon pressing the groups tab -->
            @else
                <div class ="group-header">Group: None</div>
                <div class="group-content">
                    Please select a group to view data.
                </div>
            @endif
        </div>
    </body>
<html>