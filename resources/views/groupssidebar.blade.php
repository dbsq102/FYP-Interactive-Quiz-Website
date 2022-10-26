@include('header')
        <br>
        <div class="sidebar2">
            <div class="sidebar2-container">
                <div class="sidebar-header">Group List</div>
                    <div class="sidebar2-content">
                    @if(Auth::user()->role == 0)
                        <p>You may join or view a group here.</p>
                    @else
                        <p>You may select a group to view here.</p>
                    @endif
                    <table class='quiz-table'>
                        <tr>
                            <th>Group Name</th>
                            <th>Group Description</th>
                            <th>Subject</th>
                            @if(Auth::user()->role == 0)
                                <th>View/Join</th>
                            @else  
                                <th>View</th>
                            @endif
                        </tr>
                        @foreach($allGroups as $publicGroups)
                        <tr>
                            <td>{{$publicGroups->group_name}}</td>
                            <td>{{$publicGroups->group_desc}}</td>
                            <td>{{$publicGroups->subject_name}}</td>
                            @if(Auth::user()->role == 0)
                                @if(App\Models\Member::where('user_id','=',Auth::id() )->where('group_id','=',$publicGroups->group_id)->count() == 0 )
                                    @if($publicGroups->public == 1)
                                        <td><a href="{{route('join-group', $publicGroups->group_id )}}"><img src="{{asset('/images/join.png')}}" style="width:20px"></a></td>
                                    @else
                                        <td>Private Group</td>
                                    @endif
                                @else
                                    <td><a href="{{route('groups-view', $publicGroups->group_id )}}"><img src="{{asset('/images/view.png')}}" style="width:20px"></a></td>
                                @endif
                            @else
                                <td><a href="{{route('groups-view', $publicGroups->group_id )}}"><img src="{{asset('/images/view.png')}}" style="width:20px"></a></td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                    <br>
                    @if(Auth::user()->role == 1)
                        <a class="btn btn-primary" href="{{route('create-group-view')}}">Create a new Group</a>
                    @endif
                </div>
            </div>
        </div>