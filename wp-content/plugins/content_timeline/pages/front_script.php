<?php
$my_debug=0;
$my_items_sizes=array();
$my_items_sizes['card']=array(
	'item_width'=>$settings['item-width'],
	'item_height'=>$settings['item-height'],	
	'margin'=>$settings['item-margin']		
);
$my_items_sizes['active']=array(
		'item_width'=>$settings['item-open-width'],
		'item_height'=>$settings['item-height'],
		'image_height'=>$settings['item-open-image-height']
);
if(empty($settings['autoplay'])){
	$settings['autoplay']=0;
}
if(empty($settings['autoplay-mob'])){
	$settings['autoplay-mob']=0;
}
if(empty($settings['autoplay-step'])){
	$settings['autoplay-step']=10000;
}
$my_pretty_photo='$this.find("a[rel^=\'prettyPhoto\']").prettyPhoto();';
$my_pretty_photo_1='e.element.find("a[rel^=\'prettyPhoto\']").prettyPhoto();';
$my_is_mobile=0;
if(wp_is_mobile()){
	$my_pretty_photo='';
	$my_is_mobile=1;
	$my_pretty_photo_1='';
}
$my_is_years=0;
if(isset($my_timeline_by_years)){
	$my_is_years=1;
	unset($my_timeline_by_years);
}
$frontHtml .= '

<script type="text/javascript">
my_is_mobile_global='.$my_is_mobile.';		
(function($){
var test = false;
$(window).load(function() {
	if(!test)
		timeline_init_'.$id.'($(document));			
});	';
/*
if($my_is_years){
$frontHtml.='
$(window).load(function(){
	setTimeout(function(){
		$(".t_line_node").each(function(index){
			var year = $(this).index()*20;
			$(this).attr("style", "left: "+year+"%;position: absolute; text-align: center;");
		});
	},300);	
	});';
}
*/ 
$frontHtml.='				
function timeline_init_'.$id.'($this) {
	$this.find(".scrollable-content").mCustomScrollbar();'.$my_pretty_photo.'
	$this.find("#tl'.$id.'").timeline({
		my_show_years:9,
		my_del:130,	
		my_is_years:'.$my_is_years.',	
		my_trigger_width:800,
		my_sizes		 :'.json_encode($my_items_sizes).',	
		my_id		 :'.$id.',	
		my_debug	 :'.$my_debug.',		
		is_mobile	 :  '.$my_is_mobile.',	
		autoplay     : '.$settings['autoplay'].',
		autoplay_mob :	'.$settings['autoplay-mob'].',
		autoplay_step:	'.$settings['autoplay-step'].',		
		itemMargin : '. $settings['item-margin'].',
		scrollSpeed : '.$settings['scroll-speed'].',
		easing : "'.$settings['easing'].'",
		openTriggerClass : '.$read_more.',
		swipeOn : '.$swipeOn.',
		startItem : "'. (!empty($start_item) ? $start_item : 'last') . '",
		yearsOn : '.(($settings['hide-years'] || ($settings['cat-type'] == 'categories'&&!$my_is_years )) ? 'false' :  'true').',
		hideTimeline : '.($settings['hide-line'] ? 'true' : 'false').',
		hideControles : '.($settings['hide-nav'] ? 'true' : 'false').',
		closeText : "'.$settings['close-text'].'"'.
		$cats.',
		closeItemOnTransition: '.($settings['item-transition-close'] ? 'true' : 'false').'
	});
	
	$this.find("#tl'.$id.'").on("ajaxLoaded.timeline", function(e){
		var scrCnt = e.element.find(".scrollable-content");
		scrCnt.height(scrCnt.parent().height() - scrCnt.parent().children("h2").height() - parseInt(scrCnt.parent().children("h2").css("margin-bottom")));
		scrCnt.mCustomScrollbar({theme:"light-thin"});
		'.$my_pretty_photo_1.'
		e.element.find(".timeline_rollover_bottom").timelineRollover("bottom");
	});
}
})(jQuery);
</script>';