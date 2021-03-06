/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2016 Yireo
 * @license GNU/GPL
 * @link https://www.yireo.com/
 */

jQuery(document).ready(function() {
	
    var trigger = jQuery('#simplelists-navigator a.simplelist-hover');
    var blocks = jQuery('.simplelists-item');
    
    trigger.mouseenter(function() {
        blocks.hide();
        selected = jQuery(this).attr('id').replace('simplelist-hover', 'item');
        console.log(selected);
        jQuery('#' + selected).show();
    });
    
    if( window.location.hash != '' ) {
    	hash = window.location.hash.replace('#','');
        blocks.hide();
    	jQuery('#' + hash).show();
    }
});
