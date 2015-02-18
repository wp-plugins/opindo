<a href="<?php echo admin_url('admin.php?page=opindo-admin'); ?>">Back to questions</a>
<form method="POST" action="<?php echo Api::form('store_question', 'opindo-admin'); ?>" accept-charset="UTF-8" class="add-form" enctype="multipart/form-data">
	<input type="hidden" name="created_by" value="<?php echo $_SESSION['user_id'] ;?>" />

	<h3>Question</h3>
	<label for="question">Question</label>
	<input <?php echo old_input('question'); ?> name="question" type="text" class="data" id="question">
	<?php echo error('question'); ?>


	<label for="section">Section</label>
	<select class="data" id="section" name="section">
		<option value="1">News</option>
		<option value="2">Sport</option>
	</select>
	<?php echo error('section'); ?>



	<label for="issues">Issues</label>
	<input <?php echo old_input('issues'); ?> name="issues" type="text" class="data" id="issues">
	<?php echo error('issues'); ?>


	<label for="region">Region</label>
	<input <?php echo old_input('region'); ?> name="region" type="text" class="data" id="region">
	<?php echo error('region'); ?>


	<label for="areas">Areas</label>
	<input <?php echo old_input('areas'); ?> name="areas" type="text" class="data" id="areas">
	<?php echo error('areas'); ?>


	<h3>Answer Scale</h3>
	<?php if(error('answers', true)): ?>
		<p>You need to enter all five answer parameters.</p>
	<?php endif; ?>

	1. <input <?php echo old_input('answers[1]'); ?> placeholder="eg. Agree strongly" class="data" id="answers_1" name="answers[1]" type="text">
	<br />

	2. <input <?php echo old_input('answers[2]'); ?> placeholder="eg. Agree slightly" class="data" id="answers_2" name="answers[2]" type="text">
	<br />

	3. <input <?php echo old_input('answers[3]'); ?> placeholder="eg. Neither agree nor disagree" class="data" id="answers_3" name="answers[3]" type="text">
	<br />

	4. <input <?php echo old_input('answers[4]'); ?> placeholder="eg. Disagree slightly" class="data" id="answers_4" name="answers[4]" type="text">
	<br />

	5. <input <?php echo old_input('answers[5]'); ?> placeholder="eg. Disagree strongly" class="data" id="answers_5" name="answers[5]" type="text">

	<h3>Image URL</h3>
	<input name="image" <?php echo old_input('image'); ?> type="text" class="data" id="image">
	<?php echo error('image'); ?>

	<input type="submit" name="submit_question_form" value="Save">

</form>