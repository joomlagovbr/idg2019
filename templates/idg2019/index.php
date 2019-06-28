<?php 
/**
 * @package
 * @subpackage
 * @copyright
 * @license
 */

// No direct access.
defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$this->language  = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$jinput   = JFactory::getApplication()->input;
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename','');
$metaDesc = $app->get('MetaDesc','');
$metaKey = $app->get('MetaKeys','');

$frontpage = ($option == 'com_content' && $view == 'featured');
$article = ($option == 'com_content' && $view == 'article');

require_once  JPATH_SITE .'/templates/'.$this->template.'/helper.php';
TmplIdg2019Helper::clearDefaultScripts( $this );
$active_item = TmplIdg2019Helper::getActiveItemid();


?>
<!doctype html>
<html lang="pt-br">

	<head>
		<!-- INFORMACOES A RESPEITO DO ITEM
	    ID: <?php echo InfoIdgHelper::getID()."\n"; ?>
	    ID MENU: <?php echo @$active_item->id."\n"; ?>
	    Menu vinculado: <?php echo @$active_item->menutype."\n"; ?>
	    URL completa, nao amigavel: <?php echo InfoIdgHelper::getFullURL()."\n"; ?>
	    -->
	    <?php if($active_item->home != '1') : ?>     
	        <jdoc:include type="head"/>
	    <?php else: ?>
	        <title><?php echo $sitename; ?></title>
	        <meta charset="utf-8">
	        <meta name="keywords" content="<?php echo $metaKey; ?>" />
	    <?php endif; ?>

	    <meta name="description" content="<?php echo $metaDesc; ?>" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="shortcut icon" type="image/png" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.png" />
		<!-- JS -->
		<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/script-portal.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="https://idangero.us/swiper/dist/js/swiper.min.js"></script>
		<!-- JS -->

		<!-- CSS -->
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/style.css">
		<!-- <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template-<?php echo $this->params->get('cor', 'azul'); ?>.css" type='text/css'/> -->
		<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" href="https://idangero.us/swiper/dist/css/swiper.min.css">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<!-- CSS -->  
	</head>
  
	<?php 
		$cor_capa = $active_item->params->get("menu-anchor_css");
	?>
	<body class="<?php echo strstr($cor_capa, ' ', true); ?>">

		<!-- <div class="barragov">Barra do governo</div> -->
		<jdoc:include type="modules" name="barra-do-governo" />
		<?php //var_dump($view); ?>
<!-- 		<div id="<?php //echo $active_item->home != '1' ? 'interna' : 'principal' ?>"> -->
		<div id="<?php echo $view != "default" || $active_item->home == '0' ? 'interna' : 'pginicial' ?>">

			<!-- TOPO -->
			<header class="topo">
				<nav class="menu-principal">
					<div class="container">
						
						<div class="header-wrapper">
							<!-- Logo 
							<jdoc:include type="modules" name="logo" />-->
							<div id="logo" class="span8<?php if($this->params->get('classe_nome_principal', '') != '') echo ' '.$this->params->get('classe_nome_principal'); ?>">
		                        <a href="<?php echo JURI::root(); ?>" title="<?php echo $this->params->get('nome_principal', 'Nome principal'); ?>">
		                            <?php if( $this->params->get('emblema', '') != '' ): ?>
		                            <img src="<?php echo JURI::root(); ?><?php echo $this->params->get('emblema', ''); ?>" alt="<?php echo $this->params->get('nome_principal', 'Nome principal'); ?>" />
		                            <?php endif; ?>
		                            <span class="portal-title-1"><?php echo $this->params->get('denominacao', ''); ?></span>
		                            <h1 class="portal-title corto"><?php echo $this->params->get('nome_principal', 'Nome principal'); ?></h1>
		                            <!-- <span class="portal-description"><?php //echo $this->params->get('subordinacao', ''); ?></span> -->
		                        </a>
		                    </div>

							<!-- Acessibilidade 
							<jdoc:include type="modules" name="acessibilidade" />-->
							<div class="header-accessibility">
								<ul>
									<li id="siteaction-contraste">
										<a href="#" accesskey="6" class="toggle-contraste">Alto Contraste</a>
									</li>
										<li id="siteaction-vlibras">
										<a href="http://www.vlibras.gov.br/" accesskey="">VLibras</a>
									</li>
								</ul>
							</div>

						</div> <!-- HEADER -->

						<div class="search-wrapper">
							<!-- icones -->
							<div class="header-icons">
								<a class="ico-navegacao">Navegação</a>
							</div>

							<!-- Links Destaque/Serviços -->
							<jdoc:include type="modules" name="menu-principal" />

							<!-- Search 
							<jdoc:include type="modules" name="busca" />-->
							<div id="portal-searchbox">
								<a href="javascript:void(0);" class="btn-busca" data-toggle="modal" data-target="#myModal">Buscar no portal</a>
							</div>

						</div>  <!-- MENU & BUSCA -->

						<!-- MENU PRINCIPAL -->
						<div class="box-menu">
							<div class="container">
								<div class="row">

									<!-- Menu Principal -->
									<jdoc:include type="modules" name="menu-principal-interno" />

								</div> <!-- row -->
								
								<div class="row">

									<!-- Redes Sociais Menu Principal -->
									<jdoc:include type="modules" name="redes-sociais-menu-principal" />
									
								</div>
							</div>
						</div>

					</div> <!-- CONTAINER -->
				</nav>
				<!-- BOX BUSCA -->
				<jdoc:include type="modules" name="modal-busca" />

				<!-- AREA DE DESTAQUE -->
				<?php if ($view == "default" && $active_item->home == '1') : ?>
				<jdoc:include type="modules" name="super-banner" />
				<?php endif; ?>

			</header>
			<!-- HEADER END -->

			<?php //echo '<pre>'; var_dump($view,$active_item->home) ?>
			<?php //echo '<pre>'; var_dump($view,$active_item->home) ?>
	        <?php //if (JRequest::getVar("view") == "featured" ) : ?>

	        <?php if(TmplIdg2019Helper::hasMessage()):  ?>
            <div class="row-fluid">
                <jdoc:include type="message" />
            </div>
            <?php endif; ?>

			<!--  verifica se a pagina é a inicial-->
	        <?php if ($view == "default" && $active_item->home == '1') : ?>

	        	<jdoc:include type="modules" name="pagina-inicial" style="container" headerLevel="2" />
	                     
	        <!-- verifica se a pagina é interna -->
	        <?php else: ?>

	        <div class="container">
	        	<!-- rastro de navegacao -->
	        	<jdoc:include type="modules" name="rastro-navegacao" />

			</div>

			<div class="container">
				<div class="conteudo-interna">	
		        	<?php
		                $preffix = TmplIdg2019Helper::getPagePositionPreffix($active_item);
                        $posicao_topo = $preffix. '-topo';
                        $posicao_rodape = $preffix. '-rodape';
                        $posicao_direita = $preffix. '-direita';
                        ?>
			<?php
			// adiciona o titulo da pagina
			$app	= JFactory::getApplication();
    			$menuitem   = $app->getMenu()->getActive();
			?>
						
			<?php if($menuitem->component == "com_blankcomponent"):?>
				<?php if($menuitem->params->get("menu_text")) : ?>
					<h1 class="documentFirstHeading">
						<?php if($menuitem->params->get("menu-anchor_title")) : ?>
								<?php echo $menuitem->params->get("menu-anchor_title"); ?>
							<?php else :?>
								<?php echo $menuitem->title; ?>
						<?php endif; ?>
					</h1>
				<?php endif; ?>
			<?php endif; ?>

                        <?php if($this->countModules($posicao_topo) || $this->countModules("internas-topo")): ?>
                        <div class="row-fluid">
                            <jdoc:include type="modules" name="internas-topo" headerLevel="2" style="container" />
                            <jdoc:include type="modules" name="<?php echo $posicao_topo ?>" headerLevel="2" style="container" />
                        </div>
                        <?php endif; ?>

                        <?php if($this->countModules($posicao_direita) || $this->countModules("internas-direita")): ?>
                        <div class="row-fluid">
                            <div class="span9">
                                <?php if(  TmplIdg2019Helper::isOnlyModulesPage() ): ?>
                                     <jdoc:include type="modules" name="pagina-interna-capa" style="container" headerLevel="2" />
                                     <jdoc:include type="modules" name="pagina-interna-capa-<?php echo $preffix ?>" style="container" headerLevel="2" />
                                <?php else: ?>
                                    <jdoc:include type="component" />
                                <?php endif; ?>
                            </div>
                            <div class="span3">
                                <jdoc:include type="modules" name="internas-direita" headerLevel="2" style="container" />
                                <jdoc:include type="modules" name="<?php echo $posicao_direita ?>" headerLevel="2" style="container" />
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="row-fluid">
                            <?php if(  TmplIdg2019Helper::isOnlyModulesPage() ): ?>
                                 <jdoc:include type="modules" name="pagina-interna-capa" style="container" headerLevel="2" />
                                 <jdoc:include type="modules" name="pagina-interna-capa-<?php echo $preffix ?>" style="container" headerLevel="2" />
                            <?php else: ?>
                                <jdoc:include type="component" />
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if($this->countModules($posicao_rodape) || $this->countModules("internas-rodape")): ?>
                        <div class="row-fluid">
                            <jdoc:include type="modules" name="<?php echo $posicao_rodape ?>" headerLevel="2" style="container" />
                            <jdoc:include type="modules" name="internas-rodape" headerLevel="2" style="container" />
                        </div>
                        <?php endif; ?>
		            <?php endif; ?>
	            </div>
            </div>

			<!-- FINAL CONTEUDO -->
			<jdoc:include type="modules" name="voltar-topo" />
			<!-- FINAL CONTEUDO END -->


			<!-- FOOTER -->
			<section class="footer">
				<div class="container">
					
					<div class="box-menu">
						<div class="col-md-12">
							<!-- Redes Sociais Rodapé-->
							<jdoc:include type="modules" name="rodape-redes-sociais" />

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<!-- Menu Rodapé -->
										<jdoc:include type="modules" name="menu-rodape" />
									</div>
								</div>
							</div> <!-- row -->
							
						</div>
					</div>

					<!-- Rodapé -->
					<jdoc:include type="modules" name="rodape" />
					

				</div>
			</section>
			<!-- FOOTER END -->

		</div> <!-- PRINCIPAL -->


		<!-- JS -->
    	<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/bootstrap.min.js"></script>
    	<noscript>Javascript de carregamento do Framework Bootstrap</noscript>

    	<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.cookie.js"></script> 
    	<noscript>Javascript de carregamento do Framework jQuery</noscript>

		<?php if($view == 'article'): //chamada do Flickr somente nas paginas internas ?>
	    <script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/imageflickr.js" type="text/javascript"></script><noscript><!-- item para exibição dos efeitos da galeria do FLICKR --></noscript>
	    <?php endif; ?>

    	<?php if($this->params->get('google_analytics_id', '') != ''): ?>
        <script type="text/javascript">
	          var _gaq = _gaq || [];
	          _gaq.push(['_setAccount', '<?php echo $this->params->get('google_analytics_id', ''); ?>']);
	          _gaq.push(['_trackPageview']);
	          <?php if($this->params->get('google_analytics_domain_name', '') != ''): ?>
	          _gaq.push(['_setDomainName', '<?php echo $this->params->get('google_analytics_domain_name', ''); ?>']);
	          <?php endif; ?>
	          <?php if($this->params->get('google_analytics_allow_linker', '') == 1): ?>
	          _gaq.push(['_setAllowLinker', true]);
	          <?php endif; ?>
	          (function() {
	            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	          })();
	        </script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
	    <?php endif; ?>

    	<?php if($this->countModules('barra-do-governo')) TmplIdg2019Helper::getBarra2019Script( $this ); ?>
    	
	    <!-- debug -->
	    <jdoc:include type="modules" name="debug" />
	    <?php TmplIdg2019Helper::debug( @$preffix, @$active_item); ?>

		<script>
		jQuery(document).ready(function(){	
		    var swiperDados = new Swiper('.participacao-social', {
				slidesPerView: 4,
				pagination: {
					el: '.navegacao-participacao',
					clickable: true,
				},
				navigation: {
					nextEl: '.proximo-participacao',
					prevEl: '.anterior-participacao',
				},
		    });

		    var swiper = new Swiper('.swiper-agenda', {
				slidesPerView: 3,
				spaceBetween: 30,
				slidesPerGroup: 3,
				loop: true,
				loopFillGroupWithBlank: true,
				pagination: {
					el: '.navegacao-agenda',
					clickable: true,
				},
				navigation: {
					nextEl: '.proximo-agenda',
					prevEl: '.anterior-agenda',
				},
		    });

		 	//jQuery( function() {
			//	jQuery( "#datepicker" ).datepicker();
			//});
		    
		    var $dp = jQuery( "#datepicker" );  
		    $dp.datepicker().hide();
		    jQuery("#abre-calendario").click(function(event){        
		        event.preventDefault();
		        if ($dp.is(':hidden')) {
		            $dp.show();
		        }else{
		            $dp.hide();
		        }
		    }); 
		}); 

		</script>
	    
	    <!-- JS -->
  	</body>
</html>
