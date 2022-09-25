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
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p>No quizzes found.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
