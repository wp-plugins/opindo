<select style="width:100%;" name="choose_question" id="choose_question">
	<option value="0">None</option>
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
What type of post is this?
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

<div style="clear:both"></div>