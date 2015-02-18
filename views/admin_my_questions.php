<h1>My Questions</h1>
<a href="<?php echo admin_url('admin.php?page=opindo-add-question'); ?>">Add Question</a><br/>
<?php if(success()) : ?>
	<p>This question has been <?php echo $_GET['success']; ?>.</p>
<?php endif; ?>

<table>
	<thead>
		<tr>
			<th>Question</th>
			<th>Section</th>
			<th>Issue</th>
			<th>Area</th>
			<th>Regions</th>
			<th>Responses</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (isset($questions)) {
	 	foreach($questions as $question) :?>
			<tr>
				<td>
					<a href="<?php echo admin_url('admin.php?page=opindo-question-overview&question=' . $question['id']); ?>">
						<?php echo $question['question']; ?>
					</a>
				</td>
				<td><?php
					// set URL and appropriate field(s)
					$url = '/question/sections/get/' . $question['id'];
					echo json_comma_list($url);
					?>
				</td>
				<td><?php
					// set URL and appropriate field(s)
					$url = '/question/issues/get/' . $question['id'];
					echo json_comma_list($url);
					?>
				</td>
				<td><?php
					// set URL and appropriate field(s)
					$url = '/question/areas/get/' . $question['id'];
					echo json_comma_list($url);
					?>
				</td>
				<td><?php
					// set URL and appropriate field(s)
					$url = '/question/regions/get/' . $question['id'];
					echo json_comma_list($url);
					?>
				</td>
				<td><?php
					// set URL and appropriate field(s)
					$url = '/question/responses/get/' . $question['id'];
					echo json_value($url);
					?>
				</td>
				<td>
					<a href="<?php echo admin_url('admin.php?page=opindo-edit-question&question=' . $question['id']); ?>">
						<img class="i" src="<?php echo OPINDO__PLUGIN_URL . 'images/edit.png'; ?>" alt="edit" title= "Edit" />
					</a>
					<a href="<?php echo Api::$url; ?>/api/delete/question/<?php echo $question['id']; ?>?api_key=<?php echo Api::$api_key;?>&redirect=<?php echo admin_url('admin.php?page=opindo-admin'); ?>">
						<img class="i" src="<?php echo OPINDO__PLUGIN_URL . 'images/delete.png'; ?>" alt="delete" title= "Delete" />
					</a>
				</td>
			</tr>
		<?php endforeach;
		} ?>
	</tbody>
</table>