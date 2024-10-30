<?php
function blip_adsensemenu()
{
	$blip = get_option( 'blip_settings' );
	
	if( !isset( $blip['adsense']['ads'] ) )
	{
		echo '<option value="TBC">No Ads Saved - Please Use Tools</option>';
	}
	else
	{	
		$counter = 0;

		foreach( $blip['adsense']['ads'] as $key=>$value )
		{ 
			echo '<option value="'.$value['adslot'].'" '.blip_echoselected( $blip['adsense']['adslot'],'Yes' ).'>'.$value['adslot'].' Width:'.$value['w'].' Height:'.$value['h'].'</option>';
		
			++$counter; 
		}	
	}
}
                
// lists post with sidebar videos only - also list videos under each post 
function blip_postlist_sidebarvideos( $limit )
{
	// begin building table
	$output = '
	<table class="widefat post fixed">
			<tr>
				<th width="50" scope="col">Post ID</th>
				<th width="250" scope="col">Post Title</th>
				<th scope="col"></th>
				<th scope="col"></th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>';
	
	// check if paging has been clicked
    if (isset($_GET['product_page']))
	{
        $page = $_GET['product_page'];
    }
    else
    {
        $page = 1;
    }
	
	// calculate paging starting record
    $start = ( $page - 1 ) * $limit;

	global $wpdb;
	$tablename = $wpdb->prefix . "posts";
	
	// get records from pagings start and the per page limit passed to function
	$results = $wpdb->get_results("SELECT ID,post_title FROM $tablename ORDER BY ID DESC LIMIT $start, $limit", OBJECT);
	
    //get total rows
    $totalrows = $wpdb->get_var("SELECT COUNT(*) FROM $tablename;");
	
	// if records retrieved list them
	if ($results)
	{
		foreach ($results as $post)
		{
			$blipvids = get_post_meta( $post->ID, 'bliptvid' );

			// if post meta with videos found for current post then add to list
			if( $blipvids )
			{	
				// establish number of videos in total - use to build table row span
				$bliptvcount = 0;
				
				if( $blipvids )
				{
					foreach( $blipvids as $blip )
					{
						++$bliptvcount;
					}
				}				
				
				$totalvids = $bliptvcount;	
				
				// build start of table - display post information
				$output .= '<tr>';
				$output .= '<td>'. $post->ID .'</td>';
				$output .= '<td><strong>'. $post->post_title .'</strong></td>';
				$output .= '<td><strong>Video Link</strong></td>';
				$output .= '<td><strong>Video ID</strong></td>';
				$output .= '<td><strong>Video Source</strong></td>';
				$output .= '<td><strong>Delete Video</strong></td>';
				$output .= '</tr>';			
				
				// start listing posts videos - count progress - if first apply table spans
				$vidslisted = 0;
				
				// list any blip tv ads found
				if( $blipvids )
				{
					foreach( $blipvids as $blip )
					{
						// if this is the first apply spans else apply normal row start
						if( $vidslisted == 0 )
						{
							$output .= '<tr> <td colspan="2" rowspan="'.$totalvids.'"> </td>';
						}
						else
						{
						
						}
						
						$output .= '<td><a href="http://www.blip.tv/file/'. $blip .'" title="Open the video in a new window"  target="_blank">View Video</a></td>';
						$output .= '<td>'. $blip .'</td>';
						$output .= '<td>Blip TV</td>';
						$output .= '<td><a href="admin.php?page='. $_GET['page'] .'&vididdelete='. $blip .'&postid='.$post->ID.'&vidtype=bliptvid" title="Delete this video">Delete</a></td>';
						$output .= '</tr>';		
	
						++$vidslisted;
					}
				}				
			}
		}
	}
	else
	{
	}

	$output .= '</table>';
    
    //Number of pages setup
	$pages = ceil($totalrows / $limit)+1;
    for($r = 1;$r<$pages;$r++) 
    {
        $output .= "<a href='admin.php?page=". $_GET['page'] ."&product_page=".$r."' class=\"button rbutton\">".$r."</a>&nbsp;";
    }
	
	// dislplay resulting list
    echo $output;
}

function blip_postlist_addvideotosidebar( $limit )
{
	// begin building table
	$output = '
	<table class="widefat post fixed">
			<tr>
				<th width="50" scope="col">Post ID</th>
				<th width="250" scope="col">Title</th>
				<th scope="col"></th>
			</tr>';
	
	// check if paging has been clicked
    if (isset($_GET['product_page']))
	{
        $page = $_GET['product_page'];
    }
    else
    {
        $page = 1;
    }
	
	// calculate paging starting record
    $start = ( $page - 1 ) * $limit;

	global $wpdb;
	$tablename = $wpdb->prefix . "posts";
	
	// get records from pagings start and the per page limit passed to function
	$results = $wpdb->get_results("SELECT ID,post_title FROM $tablename ORDER BY ID DESC LIMIT $start, $limit", OBJECT);
	
    //get total rows
    $totalrows = $wpdb->get_var("SELECT COUNT(*) FROM $tablename;");
	
	// if records retrieved list them
	if ($results)
	{
		foreach ($results as $record)
		{
			$output .= '<tr>';
			$output .= '<td>'. $record->ID .'</td>';
			$output .= '<td><strong>'. $record->post_title .'</strong></td>';
			$output .= '<td>
			
			<form method="post" name="blip_addvideo_postsidebar_form" action="">            
				<input name="blip_postid" type="hidden" value="'. $record->ID .'" />
                <input name="blip_videourl" type="text" size="50" maxlength="250" value="Enter URL Here" />
                <input class="button-primary" type="submit" name="blip_videotopost_sidebar" value="Sidebar" />
                <input class="button-primary" type="submit" name="blip_videotopost_content" value="Content" disabled="disabled" />
			</form>
			
			</td>';
			$output .= '</tr>';
		}
	}
	else
	{
	}

	$output .= '</table>';
    
    //Number of pages setup
	$pages = ceil($totalrows / $limit)+1;
    for($r = 1;$r<$pages;$r++) 
    {
        $output .= "<a href='admin.php?page=". $_GET['page'] ."&product_page=".$r."' class=\"button rbutton\">".$r."</a>&nbsp;";
    }
	
	// dislplay resulting list
    echo $output;
}
	
	
// displays list of adsense submitted by user	
function blip_adsense_list()
{
	$blip = get_option( 'blip_settings' );
	
	if( !isset( $blip['adsense']['ads'] ) )
	{
		echo '<strong>Notification Removed</strong><p>The plugin has delete the submitted notification.</p>';
	}
	else
	{
		echo '<table class="widefat post fixed">';

		echo '
		<tr>
			<td><strong>Array ID</strong></td>
			<td><strong>Ad Slot ID</strong></td>
			<td><strong>Width</strong></td>
			<td><strong>Height</strong></td>
			<td><strong>Delete</strong></td>
		</tr>';
	
		$counter = 0;

		foreach( $blip['adsense']['ads'] as $key=>$value )
		{ 
			echo '
			<tr>
				<td>'.$key.'</td>
				<td>'.$value['adslot'].'</td>
				<td>'.$value['w'].'</td>
				<td>'.$value['h'].'</td>
				<td><a href="admin.php?page=blip_tools&blipdeleteadsense='.$value['adslot'].'" title="Delete this AdSense ad" class="button-primary">Delete</a></td>
			</tr>';

			++$counter; 
		}	
	}
	
	echo '</table>';
}

// creates a tabled list of nofications with links to action them
function blip_listnotifications( $nots, $page )
{
	$nots = get_option( 'blip_notifications' );
	
	// do any delete passed by url
	if( isset( $_GET['notiddelete'] ) && is_numeric( $_GET['notiddelete'] ) )
	{
		unset( $nots['notifications'][ $_GET['notiddelete'] ] );
		blip_message('<strong>Notification Removed</strong><p>The plugin has delete the submitted notification.</p>');
	}
	
	echo '<table class="widefat post fixed">';
		
	echo '
	<tr>
		<td><strong>CSV File</strong></td>
		<td><strong>Description</strong></td>
		<td><strong>PHP File</strong></td>
		<td><strong>PHP Line</strong></td>
		<td><strong>Delete</strong></td>
	</tr>';
	
	$counter = 0;
	
	if( isset( $nots['notifications'] ) )
	{
		foreach( $nots['notifications'] as $not=>$notifications )
		{
			//  $not  this gets the id i.e. [0]
			
			echo '
			<tr>
				<td>'.$notifications['csvfile'].'</td>
				<td>'.$notifications['message'].'</td>
				<td>'.$notifications['phpfile'].'</td>
				<td>'.$notifications['phpline'].'</td>
				<td><a href="admin.php?page='. $page .'&notiddelete='. $not .'" title="Delete this notification" class="button-primary">Delete</a></td>
			</tr>';
					
			++$counter; 
		
			if( $counter == $nots ){ break; }
		}				
	}
	
	if( $counter == 0 )
	{
		echo '<tr><td colspan="9">No notifications were found, this would indicate that no data import or post creation events have been running</td></tr>';
	}
	
	echo '</table>';
}
?>