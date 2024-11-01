<?php
require_once('../../../wp-config.php');

//print_r($_GET);

	global $wpdb;
	$view_table = $wpdb->prefix . "views";						
	$fieldsTable= $wpdb->prefix . "viewfields";
	$postTable  = $wpdb->prefix . "posts";
	$commentTable  = $wpdb->prefix . "comments";

	switch($_GET['con']){
		case 'page'    : $filter=array('ID','post_date_gmt','ping_status','post_password','to_ping','pinged','post_modified_gmt','post_content_filtered','guid',		                                       'menu_order','post_type','post_mime_type','post_parent','comment_count','comment_status','post_category');						 
						 echo "<h3>Add Page Fields:</h3>";

		case 'post'    : if($_GET['con']=='post'){
							 echo "<h3>Add Post Fields:</h3>"; 
							 $filter=array('ID','post_date_gmt','ping_status','post_password','to_ping','pinged','post_modified_gmt','post_content_filtered','guid',		
		                                   'menu_order','post_type','post_mime_type','post_parent');
						 }// end of if($_GET['con']=='post'){	 
						  
						 $result = mysql_query('select * from '.$postTable);
							if (!$result) {
								die('Query failed: ' . mysql_error());
							}
							/* get column metadata */
							echo "<table width='100%' border='0' cellspacing='3' cellpadding='0'>";
							$i = 0;							
							while ($i < mysql_num_fields($result)) {
																
								$meta = mysql_fetch_field($result, $i);
								if (!$meta) {
									echo "No information available<br />\n";
								}
								
								if($meta->primary_key<>1 && !in_array($meta->name, $filter) ){
								
								if($_GET['con']=='page'){
									$fieldname=str_replace('Post', 'Page', ucwords(strtolower(str_replace("_"," ", $meta->name ))));
								}else{
									$fieldname=ucwords(strtolower(str_replace("_"," ", $meta->name )));
								}
								
									echo "<tr>
									<td width='15%' align='center' valign='top'><input name='postfields[]' type='checkbox' id='vfields' value='".$meta->name."-".$postTable."' onClick=\"ajaxcontent(this,'3')\"></td>
									<td width='30%' align='left' valign='top'><b>".$fieldname."</b></td>
									<td width='55%' rowspan='2' align='left' valign='top'>
									<div id='content".$meta->name."-".$postTable."'></div>
									</td>
								  </tr>
								  <tr>
									<td align='center' valign='top'>&nbsp;</td>
									<td align='left' valign='top'>&nbsp;</td>
								  </tr>";								  
							  	}
									$i++;
								}
								
								
								echo "<tr>
									<td width='15%' align='center' valign='top'><input name='postfields[]' type='checkbox' id='vfields' value='meta_value-wp_postmeta' onClick=\"ajaxcontent(this,'3')\"></td>
									<td width='30%' align='left' valign='top'><b>Custom Fields</b></td>
									<td width='55%' rowspan='2' align='left' valign='top'>
									<div id='contentmeta_value-wp_postmeta'></div>
									</td>
								  </tr>
								  <tr>
									<td align='center' valign='top'>&nbsp;</td>
									<td align='left' valign='top'>&nbsp;</td>
								  </tr>";
								
								if($_GET['con']=='post'){  
								echo "<tr>
									<td width='15%' align='center' valign='top'><input name='postfields[]' type='checkbox' id='vfields' value='name-wp_terms' onClick=\"ajaxcontent(this,'3')\"></td>
									<td width='30%' align='left' valign='top'><b>Meta Tags</b></td>
									<td width='55%' rowspan='2' align='left' valign='top'>
									<div id='contentname-wp_terms'></div>
									</td>
								  </tr>
								  <tr>
									<td align='center' valign='top'>&nbsp;</td>
									<td align='left' valign='top'>&nbsp;</td>
								  </tr>";  
								 }// end of if($_GET['con']=='post'){ 
								
								
								echo "</table>";
								
								mysql_free_result($result);
	
						  break;
						  
		case 'comment' : echo "<h3>Add Comment Fields:</h3>"; 
		
						 $result = mysql_query('select * from '.$commentTable);
							if (!$result) {
								die('Query failed: ' . mysql_error());
							}
							/* get column metadata */
							echo "<table width='100%' border='0' cellspacing='3' cellpadding='0'>";
							$i = 0;
							$filter=array('comment_ID','comment_post_ID','comment_date_gmt','comment_karma','comment_agent','comment_type','comment_parent','user_id');
							while ($i < mysql_num_fields($result)) {
																
								$meta = mysql_fetch_field($result, $i);
								if (!$meta) {
									echo "No information available<br />\n";
								}
								
								if($meta->primary_key<>1 && !in_array($meta->name, $filter) ){
								$fieldname=str_replace('Post', 'Page', ucwords(strtolower(str_replace("_"," ", $meta->name ))));
								
								echo "<tr>
								<td width='15%' align='center' valign='top'><input name='postfields[]' type='checkbox' id='vfields' value='".$meta->name."-".$commentTable."' onClick=\"ajaxcontent(this,'3')\"></td>
								<td width='30%' align='left' valign='top'><b>".$fieldname."</b></td>
								<td width='55%' rowspan='2' align='left' valign='top'>
								<div id='content".$meta->name."-".$commentTable."'></div>
								</td>
							  </tr>
							  <tr>
								<td align='center' valign='top'>&nbsp;</td>
								<td align='left' valign='top'>&nbsp;</td>
							  </tr>";
							  	}
									$i++;
								}
								
								echo "</table>";
								
								mysql_free_result($result);
								
						 break;

		case 3 :        if(!empty($_GET['conId'])) $field=explode('-', $_GET['conId']);
						 if(!empty($field[1])){
						 
						$result = mysql_query("select * from ".$field[1]);
						if (!$result) {
							die('Query failed: ' . mysql_error());
						}
						/* get column metadata */
						echo "<h3>Add Filter</h3>";
						echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
						$i = 0;								
						while ($i < mysql_num_fields($result)) {
															
							$meta = mysql_fetch_field($result, $i);
							if (!$meta) {
								echo "No information available<br />\n";
							}
							
							if($meta->name==$field[0]){
							$fieldname=str_replace('Post', 'Page', ucwords(strtolower(str_replace("_"," ", $meta->name ))));							
							switch($meta->type){								
								case 'int'    :	  $filtercondition="<b>Operation:</b><br />";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='equalto' />Is Equal To</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='notequalto' />Not Equal To</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='contains' />Contains Like</div>"; 												                                                  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='containany' />Contains Any</div>";										                                                  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='containall' />Contains All</div>";										                                                  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='notcontain' />Does Not Contain</div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='dategreaterthan' />Grater Than </div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='datelessthan' />Less Than </div>";										                                                  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='dategreaterequalto' />Grater & Equal To</div>";                                  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='datelessequalto' />Less & Equal To</div>";
												  
												  $filtertextfield="<b>Your Value:(".ucwords($meta->type).")</b><br />";
												  $filtertextfield.="<input name='filtertxt_".$meta->name."' type='text' />";
												  
												  break;
												  
								case 'string' :				  								 	
								case 'blob'   :   $filtercondition="<b>Operation:</b><br />";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='equalto' />Is Equal To</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='notequalto' />Not Equal To</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='contains' />Contains Like</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='containany' />Contains Any Word</div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='containall' />Contains All Words</div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='startswith' />Starts With</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='endswith' />Ends With</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='notcontain' />Does Not Contain</div>";										  $filtercondition.="<div>&nbsp;</div>";
												  $filtercondition.="<div><input name='case_".$meta->name."' type='checkbox' value='1' checked />Apply Case Sensitive Filter</div>";
												  $filtertextfield="<b>Your Value:(String)</b><br />";
												  $filtertextfield.="<input name='filtertxt_".$meta->name."' type='text' />";
												  
												  break;
												  
								case 'datetime' : $filtercondition="<b>Operation:</b><br />";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='dateequalto' />Is Equal To</div>";
												  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='datenotequalto' />Not Equal To</div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='dategreaterthan' />Posted After </div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='datelessthan' />Posted Before </div>";										  $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='dategreaterequalto' />On & After Date </div>";                                     $filtercondition.="<div><input name='condition_".$meta->name."' type='radio' value='datelessequalto' />On & Before Date. </div>";
												  
												  $filtertextfield="<b>Your Date:</b><br />";
												  $filtertextfield.="<input name='filtertxt_".$meta->name."' id='filtertxt_".$meta->name."' type='text' readonly />";
												  $filtertextfield.="<img src='".get_option('siteurl')."/wp-content/plugins/wp_create_view/cal/cal.gif' name='reset' id='reset' onClick=\"return showCalendar('filtertxt_".$meta->name."', 'Y-mm-dd');\" />";
												  
												  break;	  
												  
							}// end of switch($meta->type){
							
							echo "<tr>
								<td width='30%' align='left' valign='top'>".$filtercondition."</td>
								<td width='55%' rowspan='2' align='left' valign='top'>".$filtertextfield."</td>
							  </tr>";							  
							}// end of if($meta->name==$field[0]){
								$i++;
							}
							
							echo "</table>";
							
							mysql_free_result($result);
										
						 }// end of if(!empty($field[1])){
						 break;						 
						 
	}// end of switch($_GET['con']){
	
		
?>