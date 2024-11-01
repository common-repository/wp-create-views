<?php    
/*  
Plugin Name: wp-create views
Plugin URI: http://indian-manpower.com
Description: Plugin for create views to display posts,contents from database.
Author: indian-manpower.com  
Version: 1.0  
Author URI: http://www.indian-manpower.com
*/  

define("MANAGEMENT_PERMISSION", "edit_themes"); //The minimum privilege required to manage 


//Installer
function wpcreateview_install() {
require_once(dirname(__FILE__).'/wpview_installer.php');
}
register_activation_hook(__FILE__,'wpcreateview_install');



//Add the Admin Menus
if (is_admin()) {
function wpcreateview_init() {
add_menu_page("Views", "Views", MANAGEMENT_PERMISSION, __FILE__, "wpcreateview_managemenu");
add_submenu_page(__FILE__, "Manage Views", "Manage", MANAGEMENT_PERMISSION, 'view_manage', "wpcreateview_managemenu");
add_submenu_page(__FILE__, "Add Views", "Add", MANAGEMENT_PERMISSION, 'view_addedit', "wpcreateview_addeditmenu");
//add_submenu_page(__FILE__, "View Settings", "Settings", MANAGEMENT_PERMISSION, 'view_settings', "wpcreateview_settingsmenu");
//add_option('widget_view', '');
}

//Include menus
require_once(dirname(__FILE__).'/wpcreateview_menus.php');
}


//Create Widget
function show_widget_view($vid) 
{
global $wpdb;	
$wview=$wpdb->get_results($wpdb->prepare("SELECT view_field, view_query_final FROM wp_views WHERE id=%d", $vid));   
$vresults=$wpdb->get_results($wview[0]->view_query_final);  
foreach($vresults as $result){
	switch($wview[0]->view_field){
		case 'page' :echo "<h2><a href='?page_id=".$result->ID."'>".$result->post_title."</a></h2>"; break; 
		case 'post' :echo "<h2><a href='?p=".$result->ID."'>".$result->post_title."</a></h2>"; break;
		case 'comment' : echo "<h2><a href='?p=".$result->comment_post_ID."'>".substr(strip_tags($result->comment_content),0,25)."</a></h2>"; break;
	}// end of switch($wview[0]->view_field){
}// end of foreach($vresults as $result){
}// end of function show_widget_view($vid)

function widget_view($args) {

extract($args);

$options = get_option("widget_view");        

if(!empty($options['postviewid'])){
echo $before_widget;
echo $before_title;
echo "<h2><u>".$options['postviewtitle']."</u></h2>";
echo $after_title;

//Our Widget Content
show_widget_view($options['postviewid']);

echo $after_widget;
}// end of if(!empty($options['postviewid'])){

if(!empty($options['pageviewid'])){
echo $before_widget;
echo $before_title;
echo "<h2><u>".$options['pageviewtitle']."</u></h2>";
echo $after_title;

//Our Widget Content
show_widget_view($options['pageviewid']);

echo $after_widget;

}// end of if(!empty($options['pageviewid'])){

if(!empty($options['commentviewid'])){

echo $before_widget;
echo $before_title;
echo "<h2><u>".$options['commentviewtitle']."</u></h2>";
echo $after_title;

//Our Widget Content
show_widget_view($options['commentviewid']);

echo $after_widget;

}// end of if(!empty($options['commentviewid'])){

}// end of function widget_view($args) {

function view_widget_control() 
{
global $wpdb;	
$options = get_option("widget_view");
if (!is_array( $options ))
{
$options = array('postviewtitle' => 'My View Title'); 
}     

if ( $_POST['view-submit'] ) {
$newoptions['postviewid'] = htmlspecialchars($_POST['postviewid']);	
$newoptions['postviewtitle'] = htmlspecialchars($_POST['postviewtitle']);
$newoptions['pageviewid'] = htmlspecialchars($_POST['pageviewid']);	
$newoptions['pageviewtitle'] = htmlspecialchars($_POST['pageviewtitle']);
$newoptions['commentviewid'] = htmlspecialchars($_POST['commentviewid']);	
$newoptions['commentviewtitle'] = htmlspecialchars($_POST['commentviewtitle']);
update_option('widget_view', $newoptions);											
}

$options = get_option('widget_view');

$postviewid = attribute_escape($options['postviewid']);	
$pageviewid = attribute_escape($options['pageviewid']);	
$commentviewid = attribute_escape($options['commentviewid']);	

?>
<p>
<label for="postviewid">
<?php $postview=mysql_query($wpdb->prepare("SELECT id,view_name FROM wp_views WHERE view_status=%d AND view_target =%s AND view_field=%s",1,'widgets','post' )) or die(mysql_error());  
if(mysql_num_rows($postview)>0){
?>
<h2>For Posts</h2>
Post Title : <input type="text" id="postviewtitle" name="postviewtitle" value="<?php echo $options['postviewtitle'];?>" /><br />
Post View: 
<select id="postviewid" name="postviewid" >
<option value=""></option>
<?php	
while($viewRow=mysql_fetch_assoc($postview)){
if($viewRow['id']==$postviewid) $selected='selected'; else $selected='';
?>
<option value="<?=$viewRow['id']?>" <?=$selected?> ><?=$viewRow['view_name']?></option>
<?php }// end of while($viewRow=mysql_fetch_assoc($wview)){ ?>		
</select>
<?php echo "<br /><br />"; }// end of if(mysql_num_rows($postview)>0){ ?>

<?php $pageview=mysql_query($wpdb->prepare("SELECT id,view_name FROM wp_views WHERE view_status=%d AND view_target =%s  AND view_field=%s", 1, 'widgets', 'page' )) or die(mysql_error());  
if(mysql_num_rows($pageview)>0){
?>
<h2>For Pages</h2>
Page Title : <input type="text" id="pageviewtitle" name="pageviewtitle" value="<?php echo $options['pageviewtitle'];?>" /><br />
Page View: 
<select id="pageviewid" name="pageviewid" >
<option value=""></option>
<?php	
while($viewRow=mysql_fetch_assoc($pageview)){
if($viewRow['id']==$pageviewid) $selected='selected'; else $selected='';
?>
<option value="<?=$viewRow['id']?>" <?=$selected?> ><?=$viewRow['view_name']?></option>
<?php }// end of while($viewRow=mysql_fetch_assoc($wview)){ ?>		
</select>
<?php echo "<br /><br />"; }// end of if(mysql_num_rows($pageview)>0){ ?>

<?php $commentview=mysql_query( $wpdb->prepare("SELECT id,view_name FROM wp_views WHERE view_status=%d AND view_target =%s  AND view_field=%s", 1, 'widgets', 'comment') ) or die(mysql_error());  
if(mysql_num_rows($commentview)>0){
?>
<h2>For Comments</h2>
Comment Title : <input type="text" id="commentviewtitle" name="commentviewtitle" value="<?php echo $options['commentviewtitle'];?>" /><br />
Comment View: 
<select id="commentviewid" name="commentviewid" >
<option value=""></option>
<?php	
while($viewRow=mysql_fetch_assoc($commentview)){
if($viewRow['id']==$commentviewid) $selected='selected'; else $selected='';
?>
<option value="<?=$viewRow['id']?>" <?=$selected?> ><?=$viewRow['view_name']?></option>
<?php }// end of while($viewRow=mysql_fetch_assoc($wview)){ ?>		
</select>
<?php echo "<br /><br />"; }// end of if(mysql_num_rows($commentview)>0){ ?>


</label>
<br />
<small>Select view name for display.</small>
</p>			
<input type="hidden" id="view-submit" name="view-submit" value="1" />
<?php
}

function wpcreateview_widget(){	

register_sidebar_widget(__('Get View'), 'widget_view');
register_widget_control(   'Get View', 'view_widget_control', 300, 200 );  			

}// function wpcreateview_widget() {



function show_view_result($type){
global $wpdb;
$pagenotapply=0;			

$wview=mysql_query($wpdb->prepare("SELECT view_name,view_query_final FROM wp_views WHERE view_field=%s AND view_status=%d AND view_type=%s", $type, 1, 'default') ) or die(mysql_error()); 	

if(mysql_num_rows($wview)>0){
$view=mysql_fetch_assoc($wview);	

if($type=='page'){
$view_query=$view['view_query_final']." AND id='".$_GET['page_id']."'";
}else $view_query=$view['view_query_final'];

$posts = $wpdb->get_results($view_query);				
//print_r($posts);

if(!empty($posts) && $pagenotapply==0 && $type!='comment'){
inject_query_posts($posts);
}// end of if(!empty($posts)){				

if(!empty($posts) && $type=='comment'){

}// end of if(!empty($posts) && $type=='comment'){

}// end of if(mysql_num_rows($wview)>0){	
}// end of function show_view_result($vid){



function inject_query_posts( $posts, $config = array(), $query_obj = null ) {
$posts = (array) $posts;
if ( !$query_obj ) {
global $wp_query;
$query_obj = new WP_Query();
}

// Initialize the query object
if ( isset($config['query']) )
$query_obj->parse_query($config['query']); // This calls init() itself, so no need to do it here
else
$query_obj->init();
foreach ( $config as $key => $value ) {
if ( 'query' == $key ) continue;
$query_obj->$key = $value;
}
// Load the posts into the query object
$query_obj->posts = $posts;
update_post_caches($posts);
$query_obj->post_count = count($posts);
if ( $query_obj->post_count > 0 ) {
$query_obj->post = $posts[0];
$query_obj->found_posts = $query_obj->post_count;
}
if ( !isset($config['is_404']) ) // Unless explicitly told to be a 404, don't be a 404
$query_obj->is_404 = false;
$wp_query = $query_obj; // This only has any effect if wp_query was previously declared as global
return $posts;
}// end of function inject_query_posts( $posts, $config = array(), $query_obj = null ) {

/* --------------------------------- */
/* Internationalization Code - START */
/* --------------------------------- */

if (is_admin()) { 
add_action('admin_menu', 'wpcreateview_init'); 
} //Admin pages
add_action("plugins_loaded", "wpcreateview_widget");

/* --------------------------------- */
/* Internationalization Code - END */
/* --------------------------------- */	
?>