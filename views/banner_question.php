<?php
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));
$u_answer = "";

if (isset($_GET['success'])) {
    ?>
    <form method="POST" action="#" accept-charset="UTF-8" class="opindo-wrap">
        <div class="opindo-main">
            <p><?php echo $question['question']; ?></p>
            <input type="hidden" value='<?php
  $url = '/get/sameanswer/' . $question['question_id'];
  $result = Api::getData($url);
  echo $result;
  ?>' id="opindo-answer-data">
            <div id="opindo-piechart"></div>
            <p class="opindo-votes">Total Votes: <?php
                $url = '/useranswers/count/' . $question['id'];
                $result = Api::getData($url);
                $userAnswers = json_decode($result, true);
                echo $userAnswers['answers'];
            ?></p>
        </div>
        <div class="opindo-buttons">
            <button type="button" class="opindo-results-button">Results</button>
            <div class="opindo-social">
                <a class="opindo-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>"><span>Share on Facebook</span></a>
                <a class="opindo-twitter" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo urlencode($current_url);?>&hashtags=opindo"><span>Share on Twitter</span></a>
            </div>
        </div>
        <div class="opindo-footer"><a href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
    </form>
    <?php
}
elseif (isset($_POST['opindo-user-submit'])) {
    $question_id = $question['question_id'];
    $u_answer = $_POST['answer'];
    ?>
    <div class="opindo-wrap">
        <div class="opindo-main">
            <p class="opindo-header-p">See Where You Stand</p>
            <a class="opindo-facebook-login" href="<?php echo Api::login('facebook-wp', $current_url, $question_id, $u_answer); ?>">Login with Facebook</a>
            <p class="opindo-middle-p">We NEVER share or post you activity to social media</p>
            <p class="opindo-footer-p">Logging in anonymously joins your vote with others, making it a stronger voice for representatives to hear.</p>
        </div>
        <div class="opindo-footer"><a href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
    </div>
    <?php
}
else {
    ?>
    <form method="post" name="opindo-form" action="<?php echo $current_url; ?>" accept-charset="UTF-8" class="opindo-wrap">
        <div class="opindo-main">
            <p><?php echo $question['question']; ?></p>
            <div class="opindo-radio-buttons">
                <?php
                // get answers for question
                $url = '/answers/get/' . $question['question_id'];
                $result = Api::getData($url);
                $answers = json_decode($result, true);

                if(count($answers['answers']) > 0) :?>
                <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>" />
                <?php
                    $count = 0;
                    foreach($answers['answers'] as $answer) :?>
                <input id="radio<?php echo $count; ?>" name="answer" type="radio" value="<?php echo $answer['id']; ?>"<?php echo $answer['id'] == $u_answer ? "checked" : "" ?>>
                <label for="radio<?php echo $count; ?>"><?php echo $answer['name'];?><span></span></label>
                <?php
                    $count++;
                    endforeach;
                endif; ?>
            </div>
        </div>
        <div class="opindo-buttons">
            <button type="submit" name="opindo-user-submit">Submit</button>
            <div class="opindo-social">
                <a class="opindo-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>"><span>Share on Facebook</span></a>
                <a class="opindo-twitter" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo urlencode($current_url);?>&hashtags=opindo"><span>Share on Twitter</span></a>
            </div>
        </div>
        <div class="opindo-footer"><a href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
    </form>
    <?php
}
?>