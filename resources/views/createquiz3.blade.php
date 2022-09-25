@include('header')
        <br>
        <div class="col-xs-1" align="center">
            <h1>[Most Recent Quiz Name]</h1>
            <h2>Question 1</h2>
            <form>
                <input type="text" name="question_title" placeholder="Question Title"><br><br>
                <input type="text" name="answer1" placeholder="Answer 1">
                <input type="text" name="answer2" placeholder="Answer 2"><br><br>
                <input type="text" name="answer3" placeholder="Answer 3">
                <input type="text" name="answer4" placeholder="Answer 4"><br><br>
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Next Question') }}
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>