<?php
// save a new google adsense ad - we extract dimensions and id
function blip_newadsensead()
{
	if( isset( $_POST['blip_adsense_newad_submit'] ) )
	{
		$blip = get_option( 'blip_settings'); 
		
		if( !isset( $_POST['blip_adsensead'] ) || empty( $_POST['blip_adsensead'] ) )
		{
			blip_error('<strong>No AdSense Detected</strong><p>Please try again and ensure the adsense script has not been modified and is copied
						  from Google.</p>');		
		}
		else
		{		
			// remove spaces - cannot assume they exist or are correct
			$code = str_replace  (  ' '  ,  ''  , $_POST['blip_adsensead'] );
			// remove double quotes
			$code = str_replace  (  '"'  ,  ''  , $code );
			
			// split the google script code up using attributes
			$heightstring = stristr  (  $code , 'google_ad_height' );
			$widthstring = stristr  (  $code , 'google_ad_width' );
			$adslot = stristr  (  $code , 'google_ad_slot' );
			
			# size attributes #
			// remove the heightstring value in the widthstring to get width only
			$widthstring = str_replace  (  $heightstring  ,  ''  , $widthstring );
			// we now have google_ad_height=200; //-->  google_ad_width=200; - remove attribute values
			$heightstring = str_replace  (  'google_ad_height='  ,  ''  , $heightstring );
			$widthstring = str_replace  (  'google_ad_width='  ,  ''  , $widthstring );
			// use stristr to return everything after the ; that itself
			$heend = stristr  (  $heightstring , ';' );
			// replace the value of $heend in $heightstring with nothing to delete and ; in width string
			$height = str_replace  (  $heend  ,  ''  , $heightstring );
			$width = str_replace  (  ';'  ,  ''  , $widthstring );
			
			# ad_slot attribute #
			// use stristr to return everything after the ;
			$codeend = stristr  (  $adslot , ';' );		
			// remove the same result in the original code
			$adslot = str_replace  (  $codeend  ,  ''  , $adslot );
			// remove attribute itself plus equal sign
			$adslot = str_replace  (  'google_ad_slot='  ,  ''  , $adslot );
			// remove slashes
			$finaladslotid = stripslashes_deep( $adslot );

			if( empty( $height ) || empty( $width ) || empty( $finaladslotid ) )
			{
				blip_error('<strong>Invalid AdSense Detected</strong><p>The plugin could not determine required values with the
						  adsense script. Please try again and ensure the adsense script has not been modified and is copied
						  from Google.</p>');
			}
			else
			{
				$counter = 0;
			
				foreach( $blip['adsense']['ads'] as $key=>$value )
				{ 
					$blip['adsense']['ads'][$counter]['w'] = $value['w'];
					$blip['adsense']['ads'][$counter]['h'] = $value['h'];
					$blip['adsense']['ads'][$counter]['adslot'] = $value['adslot'];
					$blip['adsense']['ads'][$counter]['script'] = $value['script'];	
					++$counter; 
				}		
				
				$blip['adsense']['ads'][$counter]['w'] = $width;
				$blip['adsense']['ads'][$counter]['h'] = $height;
				$blip['adsense']['ads'][$counter]['adslot'] = $finaladslotid;
				$blip['adsense']['ads'][$counter]['script'] =  stripslashes_deep($_POST['blip_adsensead']);	
								
				if( update_option( 'blip_settings', $blip) )
				{
					blip_message('<strong>New AdSense Ad Saved</strong>');
				}
			}
		}
	}
}

// deletes sidebar video
function blip_deletesidebarvideo()
{
	if( isset( $_GET['vididdelete'] ) )
	{			
		if( delete_post_meta( $_GET['postid'], $_GET['vidtype'], $_GET['vididdelete'] ) )
		{
			blip_message('<strong>Video Deleted</strong><p>The video with id '. $_GET['vididdelete'] .' for the post id '. $_GET['postid'] .' has been deleted.</p>');
		}
		else
		{
			blip_error('<strong>Failed To Delete Video</strong><p>Video id is '. $_GET['vididdelete'] .' and post id '. $_GET['postid'] .'. The video could not be deleted from the posts custom fields. Please try again then contact webmaster@webtechglobal.co.uk for support.');
		}
	}
}

// adds a video to the content of the submitted post
function blip_addvideo_content()
{				
	if( isset( $_POST['blip_videotopost_content'] ) )
	{
		// first validate the video url
		$videourlarray = blip_validatevideourl( $_POST['blip_videourl'] );
		
		if( $videourlarray['id'] === false || $videourlarray === false )
		{
			
		}
		else
		{
			// id returned - build embed code
		}
		
		$_POST['blip_postid'];
	}
}

// assigns submitted video to a post in custom field for display in sidebar
function blip_addvideo_sidebar()
{
	if( isset( $_POST['blip_videotopost_sidebar'] ) )
	{
		// first validate the video url
		$videourlarray = blip_validatevideourl( $_POST['blip_videourl'] );
		
		if( $videourlarray['id'] === false || $videourlarray === false )
		{
			blip_error('<strong>Invalid URL Submitted</strong><p>The plugin searched for "youtube.com" or "blip.tv" within your url. It did not find these values or
					  it did not find a video id value at the end of the url. Please ensure you have the correct url and try again.</p>');
		}
		else
		{			
			// add video id to custom field for giving post id
			$metaresult = add_post_meta( $_POST['blip_postid'], $videourlarray['type'].'id' , $videourlarray['id'] );
			
			if( $metaresult )
			{
				blip_message('<strong>Video Saved For Sidebar</strong><p>Your video with the id '. $videourlarray['id'] .' was saved as a custom field for displaying in your sidebar
							when viewing the post with id '. $_POST['blip_postid'] .'.');
			}
			else
			{
				blip_error('<strong>Video Failed To Save</strong><p>Your video with the id '. $videourlarray['id'] .' failed to be added as a custom field, please try again
																										   or contact webmaster@webtechglobal.co.uk for support.');
			}
		}
	}
}

// establishes valid url - url type - returns array valid false on invalid
function blip_validatevideourl( $url )
{
	// create array - we will return type,id and url itself
	$videourlarray = array();
	$videourlarray['url'] = $url;
	
	// first detect url source - youtube or bliptv
	$videourlarray['type'] = 'TBC';
	
	// does the url contain blip.tv
	$bliptv = strpos( $videourlarray['url'],'blip.tv' );
	
	if( $bliptv == true ) 
	{
	    $videourlarray['type'] = 'bliptv';
	}	
	
	// if $urltype is still TBC return false
	if( $videourlarray['type'] == 'TBC' )
	{
		return false;
	}
	else
	{
	    if( $videourlarray['type'] == 'bliptv' )
		{
			// we need a list of possible youtube urls from different subdomanis
			$bliptv_urlarray = array("http://blip.tv/file/");
			
			// replace values from array over the full url
			$bliptvid = str_replace( $bliptv_urlarray, "",$videourlarray['url'] );
			
			// remove slashes from final value
			$videourlarray['id'] = strip_tags( stripslashes( $bliptvid ) );
			
			// if there is a youtube id value then return it
			if( $videourlarray['id'] )
			{
				return $videourlarray;
			}
			else
			{
				return false;
			}					
		}
	}
}

// remove adsense ad from plugin array
function blip_deleteadsensead()
{
	if( isset( $_GET['blipdeleteadsense'] ) && is_numeric( $_GET['blipdeleteadsense'] ) )
	{
		$blip = get_option( 'blip_settings'); 
		
		$counter = 0;
		
		foreach( $blip['adsense']['ads'] as $key=>$value )
		{ 
			if( $value['adslot'] == $_GET['blipdeleteadsense'] )
			{	
				// get adslot id for interface message
				$adslot = $value['adslot'];
				
				// delete adsense ad by array key
				unset($blip['adsense']['ads'][$key]);
				
				if( update_option( 'blip_settings', $blip) )
				{
					blip_message('<strong>AdSense Ad Deleted</strong><p>The AdSense ad with Ad-Slot ID '. $adslot .' has been remove from the plugins settings.</p>');
				}
			}
		}
	}
}

// save global default settings
function blip_saveadsensettings()
{
	if( isset( $_POST['blip_adsense_settings_submit'] ) )
	{
		$blip = get_option( 'blip_settings');
		
        $blip['adsense']['activead'] = $_POST['blip_active_adsense'];		
        $blip['adsense']['home'] = $_POST['blip_home_adsense'];
        $blip['adsense']['single'] = $_POST['blip_single_adsense'];
        $blip['adsense']['many'] = $_POST['blip_many_adsense'];	
		
		if( update_option( 'blip_settings', $blip) )
		{
			blip_message('<strong>Main Settings Saved</strong>');
		}
	}	
}		
	
// save global default settings
function blip_saveglobalsettings()
{
	if( isset( $_POST['blip_globalsettings_submit'] ) )
	{
		$blip = get_option( 'blip_settings');
		
		$blip['settings']['sidebarheight'] = $_POST['blip_sidebarheight'];
		$blip['settings']['sidebarwidth'] = $_POST['blip_sidebarwidth'];
		$blip['settings']['rotation'] = $_POST['blip_rotation'];
		
		if( update_option( 'blip_settings', $blip) )
		{
			blip_message('<strong>Main Settings Saved</strong>');
		}
	}	
}	

// save bliptv settings
function blip_savebliptvsettings()
{
	if( isset( $_POST['blip_bliptv_submit'] ) )
	{
		$blip = get_option( 'blip_settings');
		
		// BLIP TV video display styles - default video styles
		// home only video styles
		$blip['bliptv']['styles']['home']['videos'] = 2;
		// single page only video styles
		$blip['bliptv']['styles']['single']['videos'] = 2;
		// many posts/archive/category only video styles
		$blip['bliptv']['styles']['many']['videos'] = 2;
	
		if( update_option( 'blip_settings', $blip) )
		{
			blip_message('<strong>BlipTV Settings Saved</strong>');
		}
	}	
}

// reinstall all plugin settings
function blip_reinstallsettings()
{
	if( isset( $_POST['blip_reinstallsettings_submit'] ) )
	{
		delete_option( 'blip_settings' );
		
		require_once('blip_functions_installation.php');

		if( blip_insert_pluginsettings() )
		{
			blip_message('<strong>Settings Reinstalled</strong>');
		}
	}
}

?>