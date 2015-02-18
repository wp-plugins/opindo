<?php if(count($questions) > 0) : ?>
	<div class="opindo-home-list">
		<h2>Site Questions</h2>
		<ul>
			<?php foreach($questions as $question) :?>
				<?php
					$url = '/questionresource/get/' . $_SESSION['user_id'] . '/question/' . $question['id'];
					$result = Api::getData($url);
					$q_resource = json_decode($result, true);
					if($q_resource) :?>
					<li>
						<?php
							if($question['image'] != null) {
								?>
						<img src="<?php echo Api::$url .'/' . $question['image']; ?>" />
								<?php
							}
						?>
						<div class="opindo-list-txt">
							<h3>
								<a href="<?php echo $q_resource['questionresource']['url']; ?>">
									<?php echo $q_resource['questionresource']['name']; ?>
								</a>
							</h3>
							<p>
								<a href="<?php echo Api::$url; ?>/questions/<?php echo $question['id']; ?>">
									<?php echo $question['question']; ?>
								</a>
							</p>
						</div>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>