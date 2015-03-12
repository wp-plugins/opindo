<?php
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));
?>
<div id="opindo-form" class="opindo-wrap">
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
            <input id="radio<?php echo $count; ?>" name="opindo-answer" type="radio" value="<?php echo $answer['id']; ?>">
            <label for="radio<?php echo $count; ?>"><?php echo $answer['name'];?><span></span></label>
            <?php
                $count++;
                endforeach;
            endif; ?>
        </div>
    </div>
    <div class="opindo-buttons-full">
        <div class="opindo-social">
            <a class="opindo-twitter" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo urlencode($current_url);?>&hashtags=opindo"><span>Share on Twitter</span></a>
            <a class="opindo-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>"><span>Share on Facebook</span></a>
        </div>
    </div>
    <div class="opindo-footer"><a id="opindo-website" href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
</div>
<!-- Facebook Login -->
<?php
    $opindo_settings = get_option( 'opindo_settings' );
    $fbPermissions = "public_profile,email";
?>

<div id="opindo-sign-in" class="opindo-wrap">
    <div class="opindo-main">
        <input type="hidden" value="<?php echo $opindo_settings['opindo_facebook_app_id']; ?>" id="facebook-app-id">
        <input type="hidden" value="<?php echo OPINDO__PLUGIN_URL; ?>channel.php" id="channel-url">
        <input type="hidden" value="<?php echo $fbPermissions; ?>" id="facebook-scope">
        <input type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" id="ip-address">
        <input type="hidden" value="<?php echo $question['question_id']; ?>" id="opindo-question-id">
        <input type="hidden" value="" id="opindo-user-answer">
        <p class="opindo-header-p">See Where You Stand</p>
        <a id="opindo-facebook-login" rel="nofollow">Login with Facebook</a>
        <p class="opindo-middle-p">We NEVER share or post you activity to social media</p>
        <p class="opindo-footer-p">Logging in gives your voice authenticity and means you can only vote once.
            Don't worry though, we never post on your timeline or give your personal information to anyone.<br/><br/>
            P.S. Once you vote you can see how many people share your voice across the Opindo Network</p>
    </div>
    <div class="opindo-footer"><a id="opindo-website" href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
</div>

<div id="opindo-loading" class="opindo-wrap">
    <div class="opindo-main">
        <img src="<?php echo OPINDO__PLUGIN_URL; ?>images/ajax-loader.gif" />
    </div>
    <div class="opindo-footer"><a id="opindo-website" href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
</div>

<!-- Display Results -->
<div id="fb-root"></div>
<div id="opindo-message"></div>
<div id="opindo-results" class="opindo-wrap">
    <div class="opindo-main">
        <input type="hidden" value='<?php
            $url = '/get/sameanswer/' . $question['question_id'];
            $result = Api::getData($url);
            echo $result;
        ?>' id="opindo-answer-data">
        <p><?php echo $question['question']; ?></p>
        <input type="hidden" value="" id="opindo-user-id">
        <div id="opindo-load-content"></div>
        <div id="opindo-piechart"></div>
        <ul id="opindo-legend"></ul>
        <p class="opindo-votes">Total Votes: <?php
            $url = '/useranswers/count/' . $question['question_id'];
            $result = Api::getData($url);
            $userAnswers = json_decode($result);
            echo $userAnswers->answers;
        ?></p>
    </div>
    <div class="opindo-buttons">
        <button type="button" class="opindo-results-button">Results</button>
        <div class="opindo-social">
            <a class="opindo-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>"><span>Share on Facebook</span></a>
            <a class="opindo-twitter" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo urlencode($current_url);?>&hashtags=opindo"><span>Share on Twitter</span></a>
        </div>
    </div>
    <div class="opindo-footer"><a id="opindo-website" href="http://opindo.org/" target="_blank"><span>Find Your Voice</span></a></div>
</div>