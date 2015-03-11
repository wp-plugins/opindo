<div class="misc-pub-section">
<select style="width:100%;" name="choose_question" id="choose_question">
	<option value="0">Select a Question</option>
	<?php foreach($questions['questions'] as $question) :?>
		<option value="<?php echo $question['id'];?>"
			<?php
				if($current_question) {
					if($current_question['questionresource']['question_id'] == $question['id']) echo " selected";
				}
			?>
		>
		<?php echo $question['question']; ?></option>
	<?php endforeach; ?>
</select>
</div>
<div class="misc-pub-section">
<select style="float:right" name="choose_question_type" id="choose_question_type">
	<?php
	foreach($types['resourcetypes'] as $type) :?>
		<option value="<?php echo $type['id'];?>"
			<?php
				if($current_question) {
					if($current_resource['resource']['resource_type_id'] == $type['id']) echo " selected";
				}
			?>
		><?php echo $type['name']; ?></option>
	<?php endforeach; ?>
</select>
<label style="float:right; line-height:28px; margin-right: 12px;" for="choose_question_type">Post Type:</label>
</div>
<div style="clear:both"></div>