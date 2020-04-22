<!-- Remove WPFooter -->
<style>
#wpfooter {display: none;}
</style>

<form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
<?php
$options = get_option( $this->plugin_name );

$zoom_token = ( isset( $options['zoom_token'] ) && ! empty( $options['zoom_token'] ) ) ? esc_attr( $options['zoom_token'] ) : false;
$zoom_mail = ( isset( $options['zoom_mail'] ) && ! empty( $options['zoom_mail'] ) ) ? esc_attr( $options['zoom_mail'] ) : false;
$zoom_key = ( isset( $options['zoom_key'] ) && ! empty( $options['zoom_key'] ) ) ? esc_attr( $options['zoom_key'] ) : false;
$zoom_secret = ( isset( $options['zoom_secret'] ) && ! empty( $options['zoom_secret'] ) ) ? esc_attr( $options['zoom_secret'] ) : false;

settings_fields($this->plugin_name);
do_settings_sections($this->plugin_name);

?>
<div class="wrap container-fluid">

<?php 
	include_once('header.php');
?>

<hr>
    
<nav class="navbar fixed-bottom navbar-light bg-light">
	<ul class="navbar-nav ml-auto">
        	<li class="nav-item">
                
                <?php submit_button( __( 'Guardar configurações', $this->plugin_name ), 'primary','submit', true ); ?>
            	
            	</li>
  	</ul>
</nav>

</div>
</form>
</div>

<script>

// Wordpress button style override
var element = document.getElementById("submit"); 
element.classList.remove("button","button-primary");
element.classList.add("btn","btn-success");

$('#myTab a').on('click', function (e) {
  e.preventDefault()
  $(this).tab('show')
})

</script>
