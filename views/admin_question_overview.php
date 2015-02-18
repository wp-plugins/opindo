<a href="<?php echo admin_url('admin.php?page=opindo-admin'); ?>">Back to questions</a>
<h1>Question:</h1>
<a href="<?php echo admin_url('admin.php?page=opindo-edit-question&question=' . $question['id']); ?>">Edit</a>
<a href="<?php echo Api::$url; ?>/api/delete/question/<?php echo $question['id']; ?>?api_key=<?php echo Api::$api_key;?>&redirect=<?php echo admin_url('admin.php?page=opindo-admin'); ?>">Delete</a>
<a target="_blank" href="<?php echo Api::$url; ?>/questions/<?php echo $question['id']; ?>">View on Opindo.com</a>
<br />
<br />

<?php if(success()) :?>
	<p>This post has been <?php echo success(); ?>.</p>
<?php endif; ?>

<?php echo $question['question']; ?>
<?php if($question['image'] != NULL) :?>
	<img src="<?php echo Api::$url .'/' . $question['image']; ?>" alt="<?php echo $question['question']; ?>" />
<?php endif; ?>

<div>
	<p>
		<strong>Section:</strong>
		<?php
			$url = '/question/sections/get/' . $question['id'];
			echo json_comma_list($url);
		?><br />
		<strong>Issues:</strong>
		<?php
			$url = '/question/issues/get/' . $question['id'];
			echo json_comma_list($url);
		?><br />
		<strong>Areas:</strong>
		<?php
			$url = '/question/areas/get/' . $question['id'];
			echo json_comma_list($url);
		?><br />
		<strong>Regions:</strong>
		<?php
			$url = '/question/regions/get/' . $question['id'];
			echo json_comma_list($url);
		?>
	</p>
</div>

<div>
	<h1>Answer Scale:</h1>
		<?php
			$url = '/answers/get/' . $question['id'];
			$result = Api::getData($url);
			$answers = json_decode($result, true);
			foreach($answers['answers'] as $answer) :?>
				<?php echo $answer['order'] . '. ' . $answer['name']; ?><br />
		<?php
			endforeach;
		?>
</div>

<div>
	<h1>Results:</h1>
	<?php
		$url = '/useranswers/get/' . $question['id'];
		$result = Api::getData($url);
		$userAnswers = json_decode($result, true);
		foreach($userAnswers['answers'] as $name => $answer) :
			echo $name . ' - ' . ceil($answer['percentage']) . '%' . ' (' . $answer['count'] . ' votes)<br />';
		endforeach;
	?>
</div>

<div>
	<h1>This question has been featured on:</h1>
	<?php
	$url = '/question/resources/get/' . $question['id'];
	$result = Api::getData($url);
	$resources = json_decode($result, true);
	if(count($resources['resources']) > 0):?>
		<table>
			<thead>
				<th>Post</th>
				<th>URL</th>
				<th>Post Type</th>
				<th>Description</th>
				<th></th>
			</thead>
			<?php foreach($resources['resources'] as $resource) :
				$url = '/resource/get/' . $resource['pivot']['resource_id'];
				$result = Api::getData($url);
				$resource = json_decode($result);
			?>
				<tbody>
					<td><a href="<?php echo $resource->url; ?>"><?php echo $resource->name; ?></a></td>
					<td><a href="<?php echo $resource->url; ?>"><?php echo $resource->url; ?></a></td>
					<td><?php
						if ($resource->resource_type_id > 0) {
							$url = '/resourcetype/get/' . $resource->resource_type_id;
							$result = Api::getData($url);
							$type = json_decode($result);
						 	echo $type->name;
						}
						?></td>
					<td><?php echo excerpt($resource->description); ?></td>
					<td>
						<a href="<?php echo Api::$url; ?>/api/delete/questionResource/<?php echo $resource->id; ?>/<?php echo $question['id']; ?>?api_key=<?php echo Api::$api_key;?>&redirect=<?php echo admin_url('admin.php?page=opindo-question-overview'); ?>">
							<img class="i" src="<?php echo OPINDO__PLUGIN_URL ?>images/delete.png" alt="delete" title= "Delete" />
						</a>
					</td>
				</tbody>
			<?php endforeach;?>
		</table>
	<?php else : ?>
		<p>This question had not been featured on any posts yet.</p>
	<?php endif; ?>
</div>

<div>
	<h1>Comments:</h1>
	<?php
	$url = '/question/comments/get/' . $question['id'];
	$result = Api::getData($url);
	$comments = json_decode($result, true);
	if(count($comments['comments'])) :?>
		<?php foreach($comments['comments'] as $comment) :?>
			<p>
				<?php $created_at = new DateTime($comment['created_at']); echo $created_at->format('d/m/Y H:i'); ?>:
				<br />
				<?php if(!$comment['anonymous']) :?>
					<!-- <a href="/user/<?php echo $comment['user_id']; ?>"> -->
						<?php echo $comment->user->name; ?>
					<!-- </a> -->
					<br />
				<?php else :?>
					Anonymous<br />
				<?php endif; ?>
				<?php echo $comment['comment']; ?><br />
				<?php if(Auth::id() == $comment->user_id || Auth::user()->is_admin()) :?>
					<a href="/questioncomments/<?php echo $comment->id; ?>/delete">Delete</a>
				<?php endif; ?>
			</p>
		<?php endforeach;?>
	<?php else :?>
		<p>This question has not received any comments yet.</p>
	<?php endif; ?>
</div>
