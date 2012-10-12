<?php
/*

//----------------------------------------------------------------+

CUSTOM XML FOR NEXTGEN GALLERY
By Edwin Toh (www.designfission.com)

//----------------------------------------------------------------+

*/

if ( !defined('ABSPATH') ) 

	require_once( dirname(__FILE__) . '/../ngg-config.php');


global $wpdb, $ngg, $nggdb, $wp_query;

//$albumlist= $nggdb->find_all_albums('aid', 'asc', TRUE, 25, $start, false);
//$piclist = $nggdb->find_all_galleries('gid', 'asc', TRUE, 25, $start, false);

$ngg_options = get_option ('ngg_options');

$siteurl	 = site_url();

// get the gallery id

$picID = (int) $_GET['gid'];



// get the pictures

if ($picID == 0) {

	$thepictures = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE tt.exclude != 1 ORDER BY tt.{$ngg_options['galSort']} {$ngg_options['galSortDir']} ");

} else {

	$thepictures = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$picID' AND tt.exclude != 1 ORDER BY tt.{$ngg_options['galSort']} {$ngg_options['galSortDir']} ");

}

// Create XML output

header("content-type:text/xml;charset=utf-8");

echo "<gallery>\n"; 

if( $thepictures ) {
	foreach( $thepictures as $pic ) {

		$class = ( !isset($class) || $class == 'class="alternate"' ) ? '' : 'class="alternate"';
		$gid = $pic->gid;
		$name = (empty($pic->title) ) ? $pic->name : $pic->title;

		echo "	<picture>\n";

		echo "			<title>".$pic->alttext."</title>\n";
		echo "			<description>".$pic->description."</description>\n";
		echo "			<url>".$siteurl."/".$pic->path."/".$pic->filename."</url>\n";
		echo "			<thumbnail>" . esc_url(nggGallery::i18n($pic->thumbURL)) ."</thumbnail>\n";

		echo "	</picture>\n";
	}

}

echo "</gallery>\n";
?>