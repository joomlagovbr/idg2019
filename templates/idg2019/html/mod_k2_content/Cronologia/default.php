<?php
/**
 * @version    2.7.x
 * @package    K2
 * @author     JoomlaWorks http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2016 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="row">
	<?php if(count($items)): ?>
		
	<div id="timeline">			
  						  	
                  <ul id="dates" style="width: 920px; margin-left: 681px;">
					<?php foreach ($items as $key=>$item):	?>                    
                    	<li><a href="#<?php echo $item->title;  ?>" class="selected"><?php echo $item->title;  ?></a></li>
                    <?php endforeach; ?>
                                    
                  </ul>
                  <ul id="issues" style="width: 6440px; margin-left: 0px;"> 
                    <?php foreach ($items as $key=>$item):	?> 
	                    <li id="<?php echo $item->title;  ?>" class="selected" style="opacity: 1;">
	                      <div class="col-md-2">
	                        <h3><?php echo $item->title;  ?></h3>
	                      </div>        
	                      <div class="col-md-9">
	                      <div class="imagem"><?php echo $item->extra_fields[0]->value  ?></div>
	                      <div class="texto"><p><?php echo $item->extra_fields[1]->value  ?></p></div>
	                      </div>
	                    </li>
                    <?php endforeach; ?>
                    
                  </ul>
                  <div id="grad_left"></div>
                  <div id="grad_right"></div>
                  <a href="#" id="next">+</a>
                  <a href="#" id="prev" style="display: none;">-</a> 
               	

		
	 </div>
	<?php endif; ?>
</div>


