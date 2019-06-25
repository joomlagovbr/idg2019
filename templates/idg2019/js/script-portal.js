// JS
jQuery(function(){

    init();

	//acao botao de alto contraste
	jQuery('a.toggle-contraste').click(function(){
		var url = window.location.href;
		if(!jQuery('body').hasClass('contraste')){
			if(url.charAt(url.length-1) != '#'){ 
		    	jQuery("a.toggle-contraste").attr("href",url+"#");
			}
			jQuery('body').addClass('contraste'); 
			onpage_classes = 'contraste';
			jQuery.cookie('onpage_classes', onpage_classes, { 'path': '/', 'expires': 7 } );
		}else{
			if(url.charAt(url.length-1) != '#'){ 
				jQuery("a.toggle-contraste").attr("href",url+"#");
			}
			jQuery('body').removeClass('contraste');
			jQuery.cookie('onpage_classes', '', { 'path': '/' });     
		}

	});
	//fim acao botao de alto contraste
   
   	function init(){
      	//classes de layout

      	//alert(jQuery.cookie('onpage_classes'));
      	if(jQuery.cookie('onpage_classes') == 'contraste'){
         	jQuery('body').addClass( jQuery.cookie('onpage_classes') );      
      	}
   	} 


  	// ABRE O MENU PRINCIPAL
	jQuery('.header-icons').click(function(){ 
    	jQuery('.box-menu').toggleClass( "menu-ativo" );
  	});

  	// ABRE O BOX DE BUSCA
  	jQuery('#portal-searchbox').click(function(){ 
	    jQuery('.box-busca').fadeIn();
	    jQuery('.box-busca').attr("style","display:flex");
	    jQuery('body').attr("style","overflow:hidden");
	    //mantem o foco no input da modal de pesquisa.
	    jQuery('#mod-search-searchword').focus();
  	});
        
  	jQuery('.fechar-modal').click(function(){
    	jQuery('.box-busca').fadeOut();
    	jQuery('body').removeAttr("style");
  	});

});
