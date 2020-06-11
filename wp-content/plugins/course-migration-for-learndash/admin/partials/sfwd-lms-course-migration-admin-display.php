<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Sfwd_Lms_Course_Migration
 * @subpackage Sfwd_Lms_Course_Migration/admin/partials
 */

?>
<div class="container">
	<div class="row"> 
		<div class="col-md-9">
<h1>Transfer Courses</h1>

	<div class="form-group">
	<label for='ldcm_receiver_url'>Receiever's Url</label>
		<input type="text" size="50" class="form-control" id="ldcm_receiver_url" name="ldcm_receiver_url" value="" />
		
	</div>

	<div class="form-group">
	<label for='ldcm_receiver_key'>Receiver's Security Key</label>
		<input type="text" size="50" class="form-control" id="ldcm_receiver_key" name="ldcm_receiver_key" value="" />
		
	</div>

		<div class="form-group">
		<label for='course_id'>Course</label>
			<select class="form-control" name="course_id" id='course_id'>
				<option value="0">--Select Course --</option>
			<?php
			foreach ( $courses as $course ) {
				echo "<option  value={$course->ID}>{$course->post_title}</option>";
			}
			?>
			</select>
		</div>
			<input type="button" class='btn btn-primary' id='ldcm_toggle' value="Toggle All"/>

			<!-- for use in future-->
			<!-- <input type="checkbox" id="ldcm_complete_course"/><label>Migrate Complete Course</label>
		-->
			<div class="form-group">
			<div class="ldcm_course center-block">


			</div>
			</div>

	<?php


	echo '<p class="submit"><input type="button" name="ldcm_migrate" id="ldcm_migrate" class="btn btn-primary" value="Migrate"></p>';
	?>

		</div>
		<div class="col-md-3">
			<ul class="list-group">
			<li class="list-group-item">
			Add Complete url including "http://"or "https://"
			</li>
			
			</ul>
		</div>
	</div>
</div>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
