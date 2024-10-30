<?php blip_header('Blip TV - Settings'); 

// processing functions
blip_saveglobalsettings();
blip_reinstallsettings();
blip_savebliptvsettings();
blip_saveadsensettings();
	
// get options again before final output
$blip = get_option( 'blip_settings'); 
?>
<div class="postbox closed">
	<div class="handlediv" title="Click to toggle"><br /></div>
	<h3>Main Settings</h3>
	<div class="inside">
     <form method="post" name="blip_globalsettings_form" action="">                
        <a href="#" title="You can set the maximum area allowed for videos and ads. The plugin will avoid using up more than the allocated
        area. For example if you enter 500 for the heigh and your video size is set to 200, you can display 2 videos, not 3.">Maximum Sidebar Height</a>:
        <input type="text" name="blip_sidebarheight" value="<?php echo $blip['settings']['sidebarheight']; ?>" size="3" maxlength="3" />
        <br />        
        <a href="#" title="Set the maximum width for your sidebar, similiar to Maximum Sidebar Height but will actually resize all videos or ads
        to fit into your sidebar if their sizes are larger than this maximum width setting.">Maximum Sidebar Width</a>:
        <input type="text" name="blip_sidebarwidth" value="<?php echo $blip['settings']['sidebarwidth']; ?>" size="3" maxlength="3" />
        <br />        
        <a href="#" title="If a post or page has more videos connected to it than the number of videos set to display at any time time then
        rotation will avoid showing the same videos. For example if you associate 4 videos with a post and rotation is activated, 2 of the
        videos will be shown and then when refreshing the page the other 2 will be shown and it will continue like this.">Video Rotation</a>:
        <input type="text" name="blip_rotation" value="<?php echo $blip['settings']['rotation']; ?>" size="3" maxlength="3" />

        <input class="button-primary" type="submit" name="blip_globalsettings_submit" value="Save" />
      </form>
	</div>
</div> 

<div class="postbox closed">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3>AdSense Settings</h3>
    <div class="inside">
    <p>Notice: AdSense does not display on a web page instantly, it may take serveral minutes.</p>
        <form method="post" name="blip_adsense_settings_form" action=""> 
     
            <a href="#" title="">Active Ad:</a>
            <select name="blip_active_adsense" size="1">
            	<?php blip_adsensemenu(); ?>
            </select>                     
            <br />           
             <a href="#" title="">Display AdSense On Home/Front Page:</a>
            <select name="blip_home_adsense" size="1">
                <option value="Yes" <?php blip_echoselected( $blip['adsense']['home'],'Yes' ); ?>>Yes</option>
                <option value="No" <?php blip_echoselected( $blip['adsense']['home'],'No' ); ?>>No</option>
            </select>                     
            <br />
            <a href="#" title="">Display AdSense On Single Pages:</a>
            <select name="blip_single_adsense" size="1">
                <option value="Yes" <?php blip_echoselected( $blip['adsense']['single'],'Yes' ); ?>>Yes</option>
                <option value="No" <?php blip_echoselected( $blip['adsense']['single'],'No' ); ?>>No</option>
            </select>                    
            <br />
            <a href="#" title="">Display AdSense On Single Pages:</a>
            <select name="blip_many_adsense" size="1">
                <option value="Yes" <?php blip_echoselected( $blip['adsense']['many'],'Yes' ); ?>>Yes</option>
                <option value="No" <?php blip_echoselected( $blip['adsense']['many'],'No' ); ?>>No</option>
            </select>         
            <br />               
            <input class="button-primary" type="submit" name="blip_adsense_settings_submit" value="Save" />
        </form>
    </div>
</div> 
          
<div class="postbox closed">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3>BlipTV</h3>
    <div class="inside">
     <form method="post" name="blip_bliptv_form" action="">            

            <?php echo '<h4>Home/FrontPage BlipTV Settings</h4>';?>
                
            <a href="#" title="">Maximum BlipTV Videos</a>:
            <input type="text" name="blip_home_videos" value="<?php echo $blip['bliptv']['styles']['home']['videos'];?>" size="3" maxlength="3" />

            <?php echo '<h4>Apply Single Page BlipTV Settings</h4>';?>
                         
            <a href="#" title="">Maximum BlipTV Videos</a>:
            <input type="text" name="blip_single_videos" value="<?php echo $blip['bliptv']['styles']['single']['videos'];?>" size="3" maxlength="3" />
           
            <?php echo '<h4>Apply Posts And Category BlipTV Settings</h4>';?>
                         
            <a href="#" title="">Maximum BlipTV Videos</a>:
            <input type="text" name="blip_many_videos" value="<?php echo $blip['bliptv']['styles']['many']['videos'];?>" size="3" maxlength="3" />

        <input class="button-primary" type="submit" name="blip_bliptv_submit" value="Save" />
      </form>
    </div>
</div> 

<div class="postbox closed">
	<div class="handlediv" title="Click to toggle"><br /></div>
	<h3>Reset Plugin Settings</h3>
	<div class="inside">
	 <p>This will not only reset general settings but also delete data import and campaign history. It should never be used when you have
	 running campaigns as the action cannot be reversed.</p>
	 <form method="post" name="blip_reinstallsettings_form" action="">            
		  <input class="button-primary" type="submit" name="blip_reinstallsettings_submit" value="Reinstall Settings" />
	  </form>
	</div>
</div> 
        
<?php blip_footer(); ?>


