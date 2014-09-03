<head>
<style type="text/css">
	  table { margin-left:auto; margin-right:auto;}
			  
	  .image {width: 210px;}
  </style>
  </head>

<?php $this->load->view('tema/header'); ?>

<h2><?php if(!empty($title)) echo $title; ?></h2>


<div class="box-colaboradores">
	<br/>
	<p>As informações disponibilizadas por este site são fornecidas e atualizadas pelos organizações abaixo:</p>
	
	<TABLE cellpadding=3px>

			<TR> 
				
				<TD> <a target="_blank" href="http://www.desaparecidos.mg.gov.br/"><img class="image" src="<?php echo base_url('images/colaboradores/' . 'desaparecidos_mg.gif') ?>"></a> </TD>
				<TD> <a target="_blank" href="http://www.criancadesaparecida.org"><img class="image" src="<?php echo base_url('images/colaboradores/' . 'logo-desaparecida-org.jpg') ?>"></a></TD> 
			</TR> 
		
</TABLE> 

    <div class="clear"></div>    
</div>



<?php $this->load->view('tema/footer'); ?>
