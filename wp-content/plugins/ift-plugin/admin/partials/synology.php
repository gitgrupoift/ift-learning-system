<div class="row">
	
	<div class="col-md-12">
	<h4 class="display-4 align-text-bottom" style="font-size: 1.4em;"><?php printf(__('Synology API e Integrações'), $this->plugin_name); ?></h4>
	</div>
	
</div>
	<hr>
<div class="row">

<fieldset class="col-md-6 form-group">

	<div class="row">
	<div class="col">
        
    	<div class="form-group form-control-sm">
        <label for="<?php echo $this->plugin_name; ?>-syn_url">URL Base da Synology</label>
        <input type="text" class="form-control syn_url" id="<?php echo $this->plugin_name; ?>-syn_url" name="<?php echo $this->plugin_name; ?>[syn_url]" value="<?php if( ! empty( $syn_url ) ) echo $syn_url; else echo ''; ?>"/>
        <label for="<?php echo $this->plugin_name; ?>-syn_user">Utilizador de Integração</label>
        <input type="text" class="form-control syn_user" id="<?php echo $this->plugin_name; ?>-syn_user" name="<?php echo $this->plugin_name; ?>[syn_user]" value="<?php if( ! empty( $syn_user ) ) echo $syn_user; else echo ''; ?>"/>
        <label for="<?php echo $this->plugin_name; ?>-syn_pass">Palavra-passe do Utilizador</label>
        <input type="password" class="form-control syn_pass" id="<?php echo $this->plugin_name; ?>-syn_pass" name="<?php echo $this->plugin_name; ?>[syn_pass]" value="<?php if( ! empty( $syn_pass ) ) echo $syn_pass; else echo ''; ?>"/>
	</div>
	</div>
	<div class="col">
    	<div class="form-control-sm">
		<div class="alert alert-success" role="alert">
		<?php printf(__('Aqui preenche com os dados de acesso à Synology, os mesmos que se utilizam para aceder aos serviços via internet ou na aplicação desktop. O URL base é o endereço fornecido ao Grupo IFT, antes da porta "5000" que tem de preencher.'), $this->plugin_name); ?>
	        </div>
	</div>
	</div>
	</div>

</fieldset>