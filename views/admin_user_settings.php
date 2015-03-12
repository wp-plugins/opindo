<h1>Opindo Plugin Settings</h1>

<?php if(success()) : ?>
	<p>Your settings have been <?php echo $_GET['success']; ?>.</p>
<?php endif; ?>

<h3>Homepage Questions Banner</h3>
<p>Checking this box will place a summary of questions you've asked on your homepage.</p>
<form action="<?php echo Api::form('toggle', 'opindo-user-settings', $_SESSION['user_id']);?>" class="show_banner" method="POST">
	<input type="hidden" name="model" value="user" />
	<input type="hidden" name="field" value="plugin_homepage_banner" />
	<input type="checkbox" name="plugin_homepage_banner" value="true" <?php if($show_banner) echo " checked"; ?>/> Show banner
</form>
<form action='options.php' method='post'>
    <?php
    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button();
    ?>
</form>