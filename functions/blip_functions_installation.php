<?php
function blip_insert_pluginsettings()
{
	global $wpdb;
	
	$blip = array();
		
	// blog global conditions and settings - sidebar limits
	$blip['settings']['sidebarheight'] = 200;
	$blip['settings']['sidebarwidth'] = 200;
	$blip['settings']['rotation'] = 'No';// Yes allows rotation of many videos where only 1 displayed

	// BLIP TV video display styles - default video styles
	// home only video styles
	$blip['bliptv']['styles']['home']['videos'] = 2;
	// single page only video styles
	$blip['bliptv']['styles']['single']['videos'] = 2;
	// many posts/archive/category only video styles
	$blip['bliptv']['styles']['many']['videos'] = 2;
	
	// adsense on and off per area - default is global switch, the rest is for area configuration
	$blip['adsense']['activead'] = '8635280477';
	$blip['adsense']['home'] = 'Yes';
	$blip['adsense']['single'] = 'Yes';
	$blip['adsense']['many'] = 'Yes';	

	// default Google AdSense - user will be able to add as much as they want with full customisation
	$blip['adsense']['ads'][0]['w'] = '125';
	$blip['adsense']['ads'][0]['h'] = '125';
	$blip['adsense']['ads'][0]['adslot'] = '3260134959';
	$blip['adsense']['ads'][0]['script'] = '<script type="text/javascript"><!--
				google_ad_client = "pub-4923567693678329";
				/* 125x125, created 6/17/10 */
				google_ad_slot = "3260134959";
				google_ad_width = 125;
				google_ad_height = 125;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>';
	
	$blip['adsense']['ads'][1]['w'] = '200';
	$blip['adsense']['ads'][1]['h'] = '200';
	$blip['adsense']['ads'][1]['adslot'] = '8635280477';
	$blip['adsense']['ads'][1]['script'] = '<script type="text/javascript"><!--
google_ad_client = "pub-4923567693678329";
/* 200x200, created 6/17/10 */
google_ad_slot = "8635280477";
google_ad_width = 200;
google_ad_height = 200;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
				
	$blip['adsense']['ads'][2]['w'] = '250';
	$blip['adsense']['ads'][2]['h'] = '250';
	$blip['adsense']['ads'][2]['adslot'] = '4133000297';
	$blip['adsense']['ads'][2]['script'] = '<script type="text/javascript"><!--
google_ad_client = "pub-4923567693678329";
/* 250x250, created 6/17/10 */
google_ad_slot = "4133000297";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';	
	
	
	return add_option( 'blip_settings', $blip );	// will initially create option - then function can be used to reset
}
?>