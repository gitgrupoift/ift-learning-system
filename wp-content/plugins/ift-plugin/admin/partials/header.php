<div style="height: 20px;"></div>
<style>
    label {font-size: 0.75em; text-transform: uppercase; margin-top: 8px;}
</style>
	<div class="row">
        <div class="col-md-7">
            <h4 class="display-4" style="font-size: 2em;">Gestão Central - IFT Learning</h4>
        </div>
        <div class="col-md-5">
            <p class="description text-right">
            Esta aba responde por mecanismos e definições restritas aos gestores e aos desenvolvedores da plataforma. Aqui ficam as configurações relacionadas a integrações, relacionamento com outras plataformas e opções de "setup" do desenvolvimento do software.
            </p>
        </div>
    <hr>
    </div>
	
<div style="height: 20px;"></div>

<div class="col-md-12">

<div class="alert alert-success" role="alert">
	
	<h4 class="alert-heading">Bem-vindo!</h4>
  	<p>Aqui encontrará não apenas possibilidades de configuração centrais da plataforma, mas informações a respeito do seu funcionamento e atributos de privacidade e segurança condizentes com o <strong>Regulamento Geral de Proteção de Dados</strong> ou o GDPR europeu.</p>
  	<hr>
  	<p class="mb-0">Lembramos que a operação destas configurações apenas deverá ser conduzida por um dos nomeadamente responsáveis da equipa ou pelos desenvolvedores e mantenedores da plataforma.</p>
</div>

	<div style="height: 20px;"></div>
	
		<ul class="nav nav-tabs" id="myTab" role="tablist">
		  <li class="nav-item">
		    <a class="nav-link active text-success" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-lg fa-video fa-fw" style="margin-right: 10px;"></i><?php printf(__('Zoom API'), $this->plugin_name); ?></a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-success" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="far fa-lg fa-hdd fa-fw" style="margin-right: 10px;"></i><?php printf(__('Synology Apps'), $this->plugin_name); ?></a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-success" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><i class="fab fa-lg fa-google fa-fw" style="margin-right: 10px;"></i><?php printf(__('Serviços do Google'), $this->plugin_name); ?></a>
		  </li>
		</ul>

	<div style="height: 40px;"></div>
	
	<div class="tab-content" id="myTabContent">
  	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
	
	<div class="row">
		
		<div class="col-md-12">
		<h4 class="display-4 align-text-bottom" style="font-size: 1.4em;"><?php printf(__('Acedendo às configurações da Zoom API'), $this->plugin_name); ?></h4>
		</div>
		
	</div>
    
</div>
<hr>       
<div class="row">

<fieldset class="col-md-6 form-group">

	<div class="row">
	<div class="col">

	<div class="form-group form-control-sm">
        <label for="<?php echo $this->plugin_name; ?>-zoom_mail">E-mail da Conta do Zoom</label>
        <input type="email" class="form-control zoom_mail" id="<?php echo $this->plugin_name; ?>-zoom_mail" name="<?php echo $this->plugin_name; ?>[zoom_mail]" value="<?php if( ! empty( $zoom_mail ) ) echo $zoom_mail; else echo ''; ?>"/>
        <label for="<?php echo $this->plugin_name; ?>-zoom_key">Chave de Integração (ver abaixo)</label>
        <input type="text" class="form-control zoom_key" id="<?php echo $this->plugin_name; ?>-zoom_key" name="<?php echo $this->plugin_name; ?>[zoom_key]" value="<?php if( ! empty( $zoom_key ) ) echo $zoom_key; else echo ''; ?>"/>
        <label for="<?php echo $this->plugin_name; ?>-zoom_secret">Segredo de Integração (ver abaixo)</label>
        <input type="text" class="form-control zoom_secret" id="<?php echo $this->plugin_name; ?>-zoom_secret" name="<?php echo $this->plugin_name; ?>[zoom_secret]" value="<?php if( ! empty( $zoom_secret ) ) echo $zoom_secret; else echo ''; ?>"/>
	</div>
	</div>
	<div class="col">

	<div class="form-control-sm">
		<div class="alert alert-success" role="alert">
		<?php printf(__('Aqui preenche com o e-mail do registo no Zoom, e também com a chave e segredo do utilizador principal. Estes dados costumam ser necessários no caso da perda do token JWT de acesso à API e poderão ser úteis no caso de novas integrações planeadas para esta aplicação.'), $this->plugin_name); ?>
	        </div>
	</div>
	</div>
	</div>

</fieldset>

<fieldset class="col-md-6 form-group">

<div class="row">
	<div class="col">

	<div class="form-group form-control-sm">
        <label for="<?php echo $this->plugin_name; ?>-zoom_token">Token JWT - precisa ser gerada a partir da criação de uma app do desenvolvedor na Zoom API.</label>
        <input type="password" class="form-control zoom_token" id="<?php echo $this->plugin_name; ?>-zoom_token" name="<?php echo $this->plugin_name; ?>[zoom_token]" value="<?php if( ! empty( $zoom_token ) ) echo $zoom_token; else echo ''; ?>"/>
	</div>
	</div>
	<div class="col">
		<div class="form-control-sm">
		<div class="alert alert-success" role="alert">
		<?php printf(__('Aqui deverá preencher com o token fornecido aquando do registo da app JWT na API do Zoom.'), $this->plugin_name); ?>
	        </div>
	</div>
	</div>
	</div>


</fieldset>

</div>

<div style="height: 20px;"></div>
	
<div class="row">
	
	<div class="col-md-12">
	<h4 class="display-4 align-text-bottom" style="font-size: 1.6em;"><?php printf(__('Como criar uma app vinculada à API do Zoom?'), $this->plugin_name); ?></h4>
	</div>
	
</div>
	<hr>
	
	<div class="row">
	<div class="col-md-6">
		<p><?php printf(__('O <strong>Grupo IFT</strong> possui uma conta própria no Zoom, mas caso haja mudança no utilizador ou este sistema estiver sendo utilizado por outrem, os procedimentos a seguir serão necessários:'), $this->plugin_name); ?></p>
		<table class="table table-striped">
		  <thead>
		    <tr class="row">
		      <th class="col-sm-1"></th>
		      <th class="col-sm-11"><?php printf(__('Passo a Passo'), $this->plugin_name); ?></th>
	
		    </tr>
		  </thead>
		  <tbody>
		    <tr class="row">
		      <th class="col-sm-1">1</th>
		      <td class="col-sm-11"><?php printf(__('No menu do perfil, clique no item <strong>Configurações</strong> e, em seguida, na aba <strong>Reunião</strong>. Procure o subitem "Outros".'), $this->plugin_name); ?></td>
		  
		    </tr>
		    <tr class="row">
		      <th class="col-sm-1">2</th>
		      <td class="col-sm-11"><?php printf(__('No fim da página, no item "Outros", verá as informações de <strong>Autenticação de Integração</strong>. A Chave e o Segredo ali são os que deve guardar nesta página.'), $this->plugin_name); ?></td>
		 
		    </tr>
		    <tr class="row">
		      <th class="col-sm-1">3</th>
		      <td class="col-sm-11"><?php printf(__('Para gerar as tokens JWT, procederá de início clicando <a href="https://marketplace.zoom.us/" target="_blank">neste link</a>. Ali, fará o login ou sign up com as mesmas credenciais do Zoom.'), $this->plugin_name); ?></td>
		 
		    </tr>
		  </tbody>
		</table>
	</div>
	<div class="col-md-6">
		<div style="height: 20px;"></div>

	</div>
	</div>