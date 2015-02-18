<a href="<?php echo admin_url('admin.php?page=opindo-admin'); ?>">Back to questions</a>
<form class="add-form" method="POST" action="<?php echo Api::form('update_question', 'opindo-admin'); ?>" accept-charset="UTF-8" enctype="multipart/form-data">
	<input type="hidden" name="created_by" value="<?php echo $_SESSION['user_id'] ;?>" />
	<input type="hidden" name="question_id" value="<?php echo $question['id'] ;?>" />

	<h3>Question</h3>
	<label for="question">Question</label>
	<input value="<?php echo $question['question'];?>" class="data" id="question" name="question" type="text" id="question">

	<?php echo error('question'); ?>

	<?php
		// set URL and appropriate field(s)
		$url = '/question/sections/get/' . $question['id'];
		$selected_section = json_comma_list($url);
	?>
	<label for="section">Section</label>
	<select id="section" class="data" id="section" name="section"><option value="1"<?php echo $selected_section == "News" ? " selected" : "" ?>>News</option><option value="2"<?php echo $selected_section == "Sport" ? " selected" : "" ?>>Sport</option></select>
	<?php echo error('section'); ?>

	<label for="issues">Issues</label>
	<input value="<?php // set URL and appropriate field(s)
					$url = '/question/issues/get/' . $question['id'];
					echo json_comma_list($url);?>" class="data" id="issues" name="issues" type="text" id="issues">
	<?php echo error('issues'); ?>

	<label for="region">Region</label>
	<input value="<?php // set URL and appropriate field(s)
					$url = '/question/regions/get/' . $question['id'];
					echo json_comma_list($url);?>" class="data" id="region" name="region" type="text" id="region">
	<?php echo error('region'); ?>

	<label for="areas">Areas</label>
	<input value="<?php // set URL and appropriate field(s)
					$url = '/question/areas/get/' . $question['id'];
					echo json_comma_list($url);?>" class="data" id="areas" name="areas" type="text" id="areas">
	<?php echo error('areas'); ?>

	<h3>Answer Scale</h3>
	<?php if(error('answers', true)): ?>
		<p>You need to enter all five answer parameters.</p>
	<?php endif; ?>
	<?php
		// set URL and appropriate field(s)
		$url = '/answers/get/' . $question['id'];
		$result = Api::getData($url);
		$answers = json_decode($result, true);
	?>
	1. <input value="<?php echo $answers['answers'][0]['name'];?>" class="data" id="answers_1" name="answers[1]" type="text">
	<br />

	2. <input value="<?php echo $answers['answers'][1]['name'];?>" class="data" id="answers_2" name="answers[2]" type="text">
	<br />

	3. <input value="<?php echo $answers['answers'][2]['name'];?>" class="data" id="answers_3" name="answers[3]" type="text">
	<br />

	4. <input value="<?php echo $answers['answers'][3]['name'];?>" class="data" id="answers_4" name="answers[4]" type="text">
	<br />

	5. <input value="<?php echo $answers['answers'][4]['name'];?>" class="data" id="answers_5" name="answers[5]" type="text">

	<h3>Image URL</h3>
	<?php if($question['image'] != '') : ?>
		<p>Current image:</p>
		<img src="<?php echo Api::$url . '/' . $question['image']; ?>" alt="Current image" />
		<p>Change image to:</p>
	<?php endif; ?>
	<input class="data" id="image" name="image" type="text" id="image">
	<?php echo error('image'); ?>

	<input type="submit" name="submit_question_form" value="Save">

</form>