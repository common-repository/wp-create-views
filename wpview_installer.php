<?php
//***** Installer ******
require_once(ABSPATH . 'wp-admin/upgrade.php');
//***Installer variables***
global $wpdb;
$table_name = $wpdb->prefix."views";

//***Installer***
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
$sql = "CREATE TABLE " . $table_name . " (
	  id int(12) NOT NULL auto_increment,	  
	  view_name text NOT NULL,
	  view_desc text NOT NULL,
	  view_tags text NOT NULL,
	  view_type varchar(20) NOT NULL,
	  create_date varchar(50) NOT NULL,	  
	  view_status int(1) NOT NULL,
	  view_target text NOT NULL,
	  view_table varchar(50) NOT NULL,	
	  view_field varchar(50) NOT NULL,	
	  view_query text NOT NULL,
	  view_query_final text NOT NULL,
	  PRIMARY KEY  (id)
	);";
dbDelta($sql);

}

$table_name = $wpdb->prefix."viewfields";

//***Installer***
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
$sql = "CREATE TABLE " . $table_name . " (
	  field_id bigint(12) NOT NULL auto_increment,	  
	  view_id bigint(12) NOT NULL,
	  view_field_name varchar(50) NOT NULL,
	  view_table_name varchar(50) NOT NULL,	  
	  view_filter_condition varchar(50) NOT NULL,
	  view_filter_value text NOT NULL,	
	  search_case int(3) NOT NULL,  
	  PRIMARY KEY  (field_id)
	);";
dbDelta($sql);

}

//***** End Installer *****
?>