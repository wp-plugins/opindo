<h1>Trending Posts</h1>
<?php if(success()) : ?>
	<p>This resource has been <?php echo $_GET['success']; ?>.</p>
<?php endif; ?>

View trending posts from:
<select class="view">
	<option value="all">All</option>
	<option value="mine">Me</option>
	<option value="other">Opindo</option>
</select>

<table>
	<thead>
		<thead>
			<th>Post</th>
			<th>URL</th>
			<th>Post Type</th>
			<th>Description</th>
			<th>Views</th>
		</thead>
	</thead>
	<tbody>
		<?php
		if (isset($resources)) {
		foreach($resources as $resource) :?>
			<tr class="<?php echo $resource['mine'] ? 'mine' : 'other'; ?>">
				<td>
					<a href="<?php echo $resource['url']; ?>">
						<?php echo $resource['name']; ?>
					</a>
				</td>
				<td>
					<a href="<?php echo $resource['url']; ?>">
						<?php echo $resource['url']; ?>
					</a>
				</td>
				<td><?php echo $resource['resource_type']; ?></td>
				<td><?php echo excerpt($resource['description']); ?></td>
				<td><?php echo excerpt($resource['popularity']); ?></td>
			</tr>
		<?php endforeach;
		}
		?>
	</tbody>
</table>