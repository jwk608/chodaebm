<?php
if(!defined('ABSPATH'))die('');
?>
<div class="item" data-id="<?php echo $arr['dataid'] ?>" <?php $my_name=(($arr['node-name'] && $arr['node-name'] != '') ? ' data-name="'.$arr['node-name'].'"': '');echo $my_name; ?> data-description="<?php echo mb_substr($arr['item-title'],0,30);?>">
			<?php echo $image ;?>
			<div class="my_overlay">
			
			</div>
			<div class="my_timeline_content">
			<?php echo $blogDate ;?>
			
			<h2><?php echo $arr['item-title'];?></h2>
			<span><?php echo $arr['item-content'];?></span>
			
			<?php echo $readMore; ?>
			</div>
		</div>
		<div class="item_open <?php ($arr['item-image'] == '' ? ' item_open_noImg' : '') ;?>" data-id="<?php echo $arr['dataid'];?>" <?php $var=(!isset($tpreview) ? 'data-access="'.admin_url( 'admin-ajax.php' ).'?action=ctimeline_frontend_get&timeline='.$id.'&id='.$key.'"': '');echo $var;?>>
			<div class="item_open_content">
<?php 			
if(!isset($tpreview)) {
	?>
	 			<img class="ajaxloader" src="<?php echo $this->url .'images/loadingAnimation.gif';?>" alt="" />
	<?php  			
}else {
	if ($arr['item-open-image'] != '') {
					?>
			<a class="timeline_rollover_bottom con_borderImage" href="<?php (($arr['item-open-prettyPhoto'] != '')? $arr['item-open-prettyPhoto'] : $arr['item-open-image']);?>" rel="prettyPhoto[timeline]">'
	<?php 
	$image = '';
	if($arr['item-image'] != '') {
		$imgw = (int)$settings['item-open-width'];
		$imgh = (int)$settings['item-open-image-height'];
		$image = bro_images::get_image($arr['item-open-image'], $imgw, $imgh);
		$image = '<img src="'. $image .'" alt=""/>';
	}
			//$frontHtml .= '
			//'.$image. 
			echo $image;
			?>
			</a>
			<div class="timeline_open_content" style="height:<?php echo $open_content_height.'px';?>">
	<?php 	
				} 
				else { 
			?>
			<div class="timeline_open_content" style="height:<?php $my_w_12 =(intval($settings['item-height']) - 2*intval($settings['item-open-content-padding'])).'px';echo $my_w_12;?>">
			<?php 	
				}
				
				if ($arr['item-open-title'] != '') { 
				///	$frontHtml .= '
				?>
				<h2><?php echo $arr['item-open-title'];?></h2>
				<?php 
				} 
				//$frontHtml .= '
				?>
				<span <?php $my_c_12=(!isset($arr['desable-scroll']) || !$arr['desable-scroll'] ? ' class="scrollable-content"' : '');echo $my_c_12;?>>
				<?php echo stripslashes($arr['item-open-content']);?>
				</span>
				
			</div>
<?php 	
	}
?>
	</div>
	</div>
	
	<?php 

?>
