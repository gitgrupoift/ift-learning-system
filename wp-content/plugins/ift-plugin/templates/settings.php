<?php

/**
 * Definições das integrações do IFT Tools
 * @link       https://grupoift.pt
 * @since      1.4.0
 *
 * @package    IFT
 * @subpackage IFT/Settings
 */
?>

<!-- Remove WPFooter -->
<?php 

$options = get_option($this->plugin_name);

$ift_zoom_token = ( isset( $options['ift_zoom_token'] ) && ! empty( $options['ift_zoom_token'] ) ) ? $options['ift_zoom_token'] : false ; 
        
settings_fields($this->plugin_name);
do_settings_sections($this->plugin_name);

?>
<style>

#wpfooter {display: none;}

</style>

<div class="wrap container-fluid">
    
<form method="post" name="<?php echo $this->plugin_name;?>" action="options.php">    

<div style="height: 20px;"></div>
    
<div class="col-md-12">

	<div style="height: 20px;"></div>
	
	<div class="row">
		
		<div class="col-md-12">
		<h4 class="display-4 align-text-bottom" style="font-size: 1.6em;"><?php printf(__('Chaves e Tokens de acesso à API do Zoom'), $this->plugin_name); ?></h4>
		</div>
		
	</div>
<hr>

<div class="row">
	
	
<fieldset class="col-md-6 form-group">
 
    <div class="field">
    <label class="label">Token do Zoom</label>
    <div class="control">
        <input type="text" class="ift_zoom_token form-control" id="<?php echo $this->plugin_name; ?>-ift_zoom_token" name="<?php echo $this->plugin_name; ?>[ift_zoom_token]" value="<?php if( ! empty( $ift_zoom_token ) ) echo $ift_zoom_token; else echo '...'; ?>">
    </div>
    </div>

</fieldset>       

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

