
<div class="container">
	<div class="row">
  <div class="col-md-9">
  <h1>Security Key</h1>

<form method="post">

	<?php
	// $whitelist = get_option( 'ldcm_whitelist' );
	$key = get_option('ldcm_security_key');
	?>
	<table class="ldcm_whitelist form-table">

	<?php

	// foreach ( $whitelist as $url ) {
	// 	echo "
	// 	<div class='form-group'><input class='form-control' type='text' name='ldcm_whitelist[]' value='$url' /></div>
    //     ";

	// }
	// echo "
    //    <div class='form-group'> 
	// 	<input type='text' class='form-control' name='ldcm_whitelist[]' value=''/>
	//   </div>
	  
	//   <input type='button'class='btn btn-primary' value='+' id='ldcm_add_entry'/>
	//     ";
	echo "<input type='text' hint='security key' class='form-control' id='ldcm_security_key' name='ldcm_security_key' value='$key'/>";
	echo "<br/>";
	echo " <input type='button'class='btn btn-primary' value='Generate Key' id='ldcm_generate_key'/>";

	?>
	</table>
	
	<?php
	wp_nonce_field( 'ldcm_whitelist_form', 'ldcm_nonce' );
	echo '<input type="submit" name="ldcm_save_whitelist" id="ldcm_save_whitelist" class="btn btn-primary" value="Save Changes">';
	?>
</form>

  </div>
  <div class="col-md-3">
			<div class="sidebar-nav-fixed pull-right affix">
				<div class="well">
				<ul class="list-group">
					<!-- <li class="list-group-item">
						Add Complete url including "http://"or "https://"
					</li>
					<li class="list-group-item">
						By adding a url to whitelist, you're allowing that site to send LearnDash course data to your site

					</li> -->
					<li class="list-group-item">
						Once the migration is done, change the security key or deactivate the plugin for security reasons.
					</li>
				</ul>
				</div>
				<!--/.well -->
			</div>
			<!--/sidebar-nav-fixed -->
</div>
	</div>

</div>
