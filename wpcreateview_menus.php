<?php
	function wpcreateview_managemenu(){
		global $wpdb;
		$view_table = $wpdb->prefix . "views";						
		$fieldsTable= $wpdb->prefix . "viewfields";
		echo '<div class="wrap"><h2>Manage Views</h2>';
		
		
		if(isset($_GET['del']) && !empty($_GET['del'])){
			mysql_query($wpdb->prepare("DELETE FROM $fieldsTable WHERE view_id=%d", $_GET['del'])) or die(mysql_error());
			mysql_query($wpdb->prepare("DELETE FROM $view_table WHERE id=%d", $_GET['del'])) or die(mysql_error());
			echo '<div id="message" class="updated fade"><p>View details has been removed!</p></div>';
		}//end of if(isset($_GET['del']) && !empty($_GET['del'])){
		
		if(isset($_GET['act']) && !empty($_GET['act'])){			
			$viewType=mysql_query($wpdb->prepare("SELECT view_field, view_type  FROM $view_table WHERE id=%d", $_GET['act'])) or die(mysql_error());
			$type=mysql_fetch_assoc($viewType);
			mysql_query($wpdb->prepare("UPDATE $view_table SET view_status='0' WHERE view_field=%s AND view_type=%s", $type['view_field'], $type['view_type'] )) or die(mysql_error());
			mysql_query($wpdb->prepare("UPDATE $view_table SET view_status='1' WHERE id=%d", $_GET['act'])) or die(mysql_error());
			echo '<div id="message" class="updated fade"><p>Selected view has been activated!</p></div>';
		}//end of if(isset($_GET['act']) && !empty($_GET['act'])){
		
		if(isset($_GET['deact']) && !empty($_GET['deact'])){			
			mysql_query($wpdb->prepare("UPDATE $view_table SET view_status='0' WHERE id=%d", $_GET['deact'])) or die(mysql_error());
			echo '<div id="message" class="updated fade"><p>Selected view has been deactivated!</p></div>';
		}//end of if(isset($_GET['deact']) && !empty($_GET['deact'])){
		
		
		
		$viewQuery=mysql_query("SELECT * FROM $view_table") or die(mysql_error());
		if(mysql_num_rows($viewQuery)>0){
			$i=0;
			echo "<table width='100%' border='0' cellspacing='3' cellpadding='0'>
  <tr>
  <td width='2%' style='border:1px solid #cccccc;' align='left'><b>S.No.</b></td>
  <td width='20%' style='border:1px solid #cccccc;' align='left'><b>View Name</b></td>
<td width='32%' style='border:1px solid #cccccc;' align='left'><b>Description</b></td><td width='15%' style='border:1px solid #cccccc;' align='left'><b>Create Date</b></td><td width='13%' style='border:1px solid #cccccc;' align='left'><b>Created For</b></td>
<td width='20%' style='border:1px solid #cccccc;' align='left'><b>Action</b></td></tr>";
		
				 while($viewRow=mysql_fetch_assoc($viewQuery)){
				 	$i=$i+1;
				 	echo "<tr>
					<td width='2%' style='border:1px solid #cccccc;' align='left' valign='top'><b>".$i.".</b></td>
					<td width='20%' style='border:1px solid #cccccc;' align='left' valign='top'>".$viewRow['view_name']."</td>
<td width='32%' style='border:1px solid #cccccc;' align='left' valign='top'>".$viewRow['view_desc']."<br />Tags: ".$viewRow['view_tags']."</td><td width='15%' style='border:1px solid #cccccc;' align='left' valign='top'>".$viewRow['create_date']."</td><td width='13%' style='border:1px solid #cccccc;' align='left' valign='top'><b>".$viewRow['view_field']."</b><br /><br />Display At:  <u>".$viewRow['view_type']."</u><br>".$viewRow['view_target']."</td>
<td width='20%' style='border:1px solid #cccccc;' align='center' valign='middle'><a href='".get_option("siteurl")."/wp-admin/admin.php?page=view_addedit&edit=".$viewRow['id']."'>Edit</a>"; 

if($viewRow['view_status']=='1'){
echo " | <a href='".get_option("siteurl")."/wp-admin/admin.php?page=view_manage&deact=".$viewRow['id']."'>Deactivate</a>";
}else{
echo " | <a href='".get_option("siteurl")."/wp-admin/admin.php?page=view_manage&act=".$viewRow['id']."'>Activate</a>";
}

echo "| <a href='".get_option("siteurl")."/wp-admin/admin.php?page=view_manage&del=".$viewRow['id']."' onClick=\"javascript:return confirm('Are you sure to remove this view!');\">Remove</a>
</td></tr> <tr><td style='border:1px solid #cccccc;' align='left' valign='middle'><b>Query :</b> </td>
    <td colspan='5' align='left' style='border:1px solid #cccccc;'>".stripslashes(str_replace(':', ' , ', $viewRow['view_query']))."</td>
    </tr>";
	
				 }// end of while($viewRow=mysql_fetch_assoc($viewQuery)){
	echo "<tr><td>&nbsp;</td>
    <td colspan='5' align='left'>&nbsp;</td>
    </tr>";

	 echo '<tr><td style="border:1px solid #cccccc;" align="left" valign="middle"><b>Note :</b> </td>
    <td colspan="5" align="left" style="border:1px solid #cccccc;">
	<ul>
	<li>You should "Activate" your plugin for reflect its effect at client side.</li>
	<li>Write &lt;?php show_view_result("post"); ?&gt; at the top of your page on which you display post according to the view. </li>
	<li>Write &lt;?php show_view_result("page"); ?&gt; at the top of your page on which you display page contents according to the view. </li>
	<li>You can also create widgets through this plugin for display post, page and comment links at your sidebar of the pages. </li>
	</ul>
	</td>
    </tr>';

echo "</table>";
				 			
		}// end of if(mysql_num_rows($viewQuery)>0){
		else echo "<div align='center'><h3>Views are not created yet!</h3></div>";
		
		
	}// end of function wpcreateview_managemenu(){
	
	function wpcreateview_addeditmenu(){		
		global $wpdb;
		$view_table = $wpdb->prefix . "views";						
		$fieldsTable= $wpdb->prefix . "viewfields";
		
		if ($_POST['Submit']) {	
				
			$post_editview = $wpdb->escape($_POST['edit']);
			$post_view_name = $wpdb->escape($_POST['view_name']);
			$post_view_desc = $wpdb->escape($_POST['view_desc']);
			$post_view_tags = $wpdb->escape($_POST['view_tags']);
			$post_view_type = $wpdb->escape($_POST['view_type']);
			$view_field=$wpdb->escape($_POST['view_field']);
				
			if($_POST['view_type']=='page') $post_view_target=get_option("siteurl").'/?path_id='.strtolower(str_replace(" ", "_", $_POST['view_name'])); 
			else $post_view_target=$_POST['view_type'];
			
			switch($_POST['view_field']){
				case 'post'    : 
				case 'page'    : $view_tablename=$wpdb->prefix."posts"; break;
				case 'comment' : $view_tablename=$wpdb->prefix."comments"; break;
			}// end of switch($_POST['view_field']){
						
			if ($post_editview=='') {
			$results = $wpdb->query($wpdb->prepare("INSERT INTO $view_table (view_name, view_desc, view_tags, view_type, create_date, view_target,view_table,view_field ) VALUES (%s, %s, %s, %s, now(), %s, %s, %s)", $post_view_name, $post_view_desc, $post_view_tags, $post_view_type, $post_view_target, $view_tablename, $view_field ));
			$theid=mysql_insert_id();
			echo '<div id="message" class="updated fade"><p>View &quot;'.$post_view_name.'&quot; has been created.</p></div>';
			} else {			
			$results = $wpdb->query($wpdb->prepare("UPDATE $view_table SET view_name = %s, view_desc = %s,  view_tags = %s, view_type = %s, view_target=%s, view_table=%s, view_field=%s WHERE id=%d", $post_view_name, $post_view_desc, $post_view_tags,  $post_view_type, $post_view_target, $view_tablename, $view_field, $post_editview ));
			
			mysql_query($wpdb->prepare("DELETE FROM $fieldsTable WHERE view_id=%d", $post_editview)) or die(mysql_error());
			$theid=$post_editview;
			
			echo '<div id="message" class="updated fade"><p>View &quot;'.$post_view_name.'&quot; has been updated.</p></div>';
			}
			
			
			
			if(!empty($_POST['postfields'])){
				foreach($_POST['postfields'] as $fields){
					if(!empty($fields)){
						$fieldvalue=explode('-', $fields);
						$condition=$_POST['condition_'.$fieldvalue[0]];
						$filterValue=$_POST['filtertxt_'.$fieldvalue[0]];
						$search_case=$_POST['case_'.$fieldvalue[0]];
																	
						$results = $wpdb->query($wpdb->prepare("INSERT INTO $fieldsTable (view_id, view_field_name, view_table_name, view_filter_condition, view_filter_value, search_case) VALUES(%d, %s, %s, %s, %s, %s)", $theid, $fieldvalue[0], $fieldvalue[1], $condition, $filterValue, $search_case ));
							
					}// end of if(!empty($fields)){
				}// end of foreach($_POST['postfields'] as $fields){								
				
				$viewQuery=generateQuery($theid);
								
			    $results = $wpdb->query($wpdb->prepare("UPDATE $view_table SET view_query = %s WHERE id=%d", addslashes($viewQuery), $theid ));
				
			}// end of if(!empty($_POST['postfields'])){
			
			
		}// end of if ($_POST['Submit']) {
		
		
		
		//If post is being edited, grab current info
		if ($_REQUEST['edit']!='') {
		$theid = $_REQUEST['edit'];			
			echo '<div class="wrap"><h2>Edit View</h2>';
		}else echo '<div class="wrap"><h2>Add View</h2>';
		
		$editview = $wpdb->get_row("SELECT * FROM $view_table WHERE id = '$theid'", OBJECT);
	?>	
	
	
	<script language="javascript">
	var divid;
	function ajaxcontent(id, obj)
	{					
		if(id=='2'){
		txt=obj.value;
		divid='content'+id;
		
		}else if(obj=='3'){
			 var temp=id.value;
			 txt=obj;
			 divid='content'+temp;		 			 	 
			 if(id.checked==false){
		 	   document.getElementById(divid).innerHTML='';
			   return;
		     }else id=temp;			 
		}	
						
		var xmlHttp;
		
		try
		{        
			xmlHttp=new XMLHttpRequest();    // Firefox, Opera 8.0+, Safari
		}
		catch (e)
		{    // Internet Explorer    
			try
			{      
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");      
			}
			catch (e)
			{      
				try
				{        
					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");        
				}
				catch (e)
				{        
					alert("Your browser does not support AJAX!");        
					return false;        
				}      
			}    
		}
		
		//alert(divid);
		
		document.getElementById(divid).innerHTML="<img src='<?=get_option("siteurl")?>/wp-content/plugins/wp_create_view/images/status.gif'>";
		
		xmlHttp.onreadystatechange=function()
		{
			if(xmlHttp.readyState==4)
			{			
				//alert(xmlHttp.responseText);
				document.getElementById(divid).innerHTML=xmlHttp.responseText;				
			}
		}
		xmlHttp.open("GET","<?=get_option("siteurl")?>/wp-content/plugins/wp_create_view/getview_content.php?conId="+id+"&con="+txt,true);
		
		xmlHttp.send(null);	
	}
	
	
function chkViewform(frmObj){
	if(frmObj.view_name.value==""){
		alert("Enter your view name!");
		return false;
	}
	if(frmObj.view_desc.value==""){
		alert("Enter your view description!");
		return false;
	}
	if(frmObj.view_field.value==""){
		alert("Please select view field!");		
		return false;
	}else{		
		for (var i =0; i < frmObj.elements.length; i++) 
		{
		 	if(frmObj.elements[i].type=='checkbox' && frmObj.elements[i].id && frmObj.elements[i].checked){
				 return true;			 
			}	 
		}
		alert("Please select field name for apply in view!");
		return false;
	}
	 	
	return false;	
}
	
</script>

<script language="javascript" src="<?=get_option("siteurl")?>/wp-content/plugins/wp_create_view/cal/calendar.js"></script>
<script language="javascript" src="<?=get_option("siteurl")?>/wp-content/plugins/wp_create_view/cal/calendar-en.js"></script>
<script language="javascript" src="<?=get_option("siteurl")?>/wp-content/plugins/wp_create_view/cal/mambojavascript.js"></script>

<link type="text/css" rel="stylesheet" href="<?=get_option("siteurl")?>/wp-content/plugins/wp_create_view/cal/calendar-mos.css" />
	
	
		<form method="post" name="viewfrm" action="admin.php?page=view_addedit" onSubmit="return chkViewform(this);">	
<table class="form-table">

<?php if ($_REQUEST['edit']!='') { echo '<input name="edit" type="hidden" value="'.$_REQUEST['edit'].'" />'; } ?>

<tr valign="top">
<th scope="row">View Name <span style="color:#FF0000;">*</span></th>
<td><input name="view_name" type="text" id="view_name" value="<?php echo $editview->view_name; ?>" size="50" />
<br/>This is the unique name of the view. It must contain only alphanumeric characters and underscores; it is used to identify the view internally and to generate unique theming template names for this view. If overriding a module provided view, the name must not be changed or instead a new view will be created.</td>
</tr>

<tr valign="top">
<th scope="row">View Description<span style="color:#FF0000;">*</span></th>
<td><label>
  <textarea name="view_desc" cols="49" rows="4" id="view_desc"><?php echo $editview->view_desc; ?></textarea>
  <br />This description will appear on the Views administrative UI to tell you what the view is about.
</label></td>
</tr>
<tr valign="top">
  <th scope="row">View Tags </th>
  <td><input name="view_tags" type="text" id="view_tags" size="50" value="<?php echo $editview->view_tags; ?>">
    <br />Enter an optional tag for this view; it is used only to help sort views on the administrative page.</td>
</tr>
<tr valign="top">
  <th scope="row">View Display At <span style="color:#FF0000;">*</span></th>
  <td>
  <?php
  	switch($editview->view_type){
		case 'page' : $page='checked'; break;
		case 'widgets' : $widgets='checked' ; break;
		default        : $default='checked' ; break;
	}// end of switch($editview->view_type){
  ?>
  <!--<input name="view_type" type="radio" value="page" <?=$page?>> Page--> 
  <input name="view_type" type="radio" value="default" <?=$default?>> Default 
  <input name="view_type" type="radio" value="widgets" <?=$widgets?>> Widgets  
  <div id="content1">
  <?php
  	if($editview->view_type=='page'){
  		echo "<b>Page Path: </b>".get_option("siteurl").'/?path_id='.strtolower(str_replace(" ", "_", $editview->view_name));
	}elseif($_POST['view_type']=='page') {
		echo "<b>Page Path: </b>".get_option("siteurl").'/?path_id='.strtolower(str_replace(" ", "_", $_POST['view_name']));
	}
	if($editview->view_query!=''){
		$viewQuery=$editview->view_query;
	}
  ?>
  </div>  </td>
</tr>
<tr valign="top">
  <th scope="row">Select Field<span style="color:#FF0000;">*</span></th>
  <td>
  		<?php
			switch($editview->view_field){
				case 'post' : $post='selected'; break;
				case 'page' : $page='selected'; break;
				case 'comment' : $comment='selected'; break;
			}
		?>
		<select name="view_field" id="view_field" onchange="ajaxcontent('2', this)">
			<option value="">Select Field</option>
			<option value="post" <?=$post?>>Post</option>			
			<option value="page" <?=$page?>>Page</option>
			<option value="comment" <?=$comment?>>Comment</option>			
		</select>  </td>
</tr>

<tr valign="top">
  <th scope="row">&nbsp;</th>
  <td>
  <div id="content2"></div>  </td>
</tr>

<tr valign="top">
  <th scope="row">&nbsp;</th>
  <td>
  
  <p class="submit"><input type="submit" name="Submit" value="Save View" /> 
&nbsp;
<?php if ($_POST['Submit'] || $_GET['edit']!='' ) { ?><!--<input type="submit" name="preview" value="Preview" />-->
<?php } ?>
</p>
  
  </td>
</tr>

<?php if(!empty($viewQuery)){ 
$viewQueryArr=explode(":", $viewQuery);
?>
<tr valign="top">
  <th scope="row">Preview</th>
  <td><b>Query:</b> <?=stripslashes($viewQueryArr[0]).", ".stripslashes($viewQueryArr[1])?> </td>
</tr>
<tr valign="top">  
  <td colspan="2" align="justify">
  <?php  
	$vr=explode(",", stripslashes($viewQueryArr[1]));
	
	$vQuery = $wpdb->prepare(stripslashes($viewQueryArr[0]) , trim($vr[0]), trim($vr[1]), trim($vr[2]), trim($vr[3]), trim($vr[4]), trim($vr[5]), trim($vr[6]), trim($vr[7]), trim($vr[8]), trim($vr[9]), trim($vr[10]), trim($vr[11]), trim($vr[12]), trim($vr[13]), trim($vr[14]), trim($vr[15]), trim($vr[16]), trim($vr[17]), trim($vr[18]), trim($vr[19]), trim($vr[20]), trim($vr[21]), trim($vr[22]), trim($vr[23]), trim($vr[24]), trim($vr[25]), trim($vr[26]), trim($vr[27]), trim($vr[28]), trim($vr[29]));		
	
	mysql_query($wpdb->prepare("UPDATE ".$wpdb->prefix."views SET view_query_final=%s WHERE id=%d", str_replace('"', '',$vQuery), $theid) ) or die(mysql_error());
	
	$viewResult=mysql_query(str_replace('"', '',$vQuery)) or die(mysql_error());	
	
	if(!empty($theid)){		
		$fieldsTable= $wpdb->prefix . "viewfields";
		$viewFields=mysql_query("SELECT view_field_name FROM $fieldsTable WHERE view_id=".$theid) or die(mysql_error());
		$totalfields=mysql_num_rows($viewFields);
		if($totalfields > 0){
			$tdwidth=ceil(100/$totalfields);
			echo "<table width='100%' border='0' cellspacing='3' cellpadding='0'><tr>";
			while($fieldRow=mysql_fetch_assoc($viewFields)){
				echo "<td width='".$tdwidth."%' style='border:1px solid #cccccc;' align='left'>".ucwords(str_replace('_', ' ', $fieldRow['view_field_name']))."</td>";
			}
			echo "</tr></table>";
		}// end of if($totalfields > 0){
	}// end of if(!empty($theid)){
  ?>
  <table width='100%' border='0' cellspacing='0' cellpadding='0'>
  	<?php if(mysql_num_rows($viewResult)<=0){ ?>
	<tr>
	<td>
		No Result found, by above generated query!
	</td>
	</tr>
	<?php }else{ ?>
	<tr>
	<td>
		<table width='100%' border='0' cellspacing='3' cellpadding='0'>
		<?php while($viewRow=mysql_fetch_assoc($viewResult)){ ?>
		<tr>
		<?php
			$viewFields=mysql_query($wpdb->prepare("SELECT view_field_name FROM $fieldsTable WHERE view_id=%d", $theid )) or die(mysql_error());
		 	while($fieldRow=mysql_fetch_assoc($viewFields)){
				echo "<td width='".$tdwidth."%' align='justify' valign='top'>".substr(strip_tags($viewRow[$fieldRow['view_field_name']]),0,150)."</td>";
			}
		?>
		</tr>
		<?php }// end of while($viewRow=mysql_fetch_assoc($viewResult)){ ?>
		</table>	
	</td>
	</tr>
	<?php } ?>
  </table>
  </td>
</tr>
<?php } //end of if(!empty($viewQuery)){  ?>
</table>
</form>
			
	<?php
	}// end of function wpcreateview_addeditmenu(){
	
	function wpcreateview_settingsmenu(){
		echo '<div class="wrap"><h2>View Settings</h2>';
	}// end of function wpcreateview_settingsmenu(){
	
	function remove_array_empty_values($array, $remove_null_number = true)
	{
		$new_array = array();
	
		$null_exceptions = array();
	
		foreach ($array as $key => $value)
		{
			$value = trim($value);
	
			if($remove_null_number)
			{
				$null_exceptions[] = '0';
			}
	
			if(!in_array($value, $null_exceptions) && $value != "")
			{
				$new_array[] = $value;
			}
		}
		return $new_array;
	}
	
	
	function generateQuery($theid){		
		global $wpdb;
		if(!empty($theid)){			
			$view_query=mysql_query($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."viewfields WHERE view_id = %d order by field_id ASC", $theid )) or die(mysql_error());
			if(mysql_num_rows($view_query)>0){
				$viewQuery="SELECT ";
				$tablename=array();
				$whereValues=array(3);				
				$i=0;
				$selectFields='';
				$groupFields='';
				$fromTables=array();
				$whereCondition="WHERE 2<%d ";				
				
				while($view_row=mysql_fetch_assoc($view_query)){
				
					if(!in_array($view_row['view_table_name'], $tablename)){
						
						$i=$i+1;
						
						$tablename[$i]=$view_row['view_table_name'];
						$tbl='tbl'.$i;	
						
						$fromTables[]=$view_row['view_table_name']." ".$tbl." ";
						
						$editview = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."views WHERE id = '$theid'", OBJECT);
							
						switch($editview->view_field){									
							case 'page' :	$whereCondition.="AND ".$tbl.".post_type  = %s ";
											$whereValues[]='page';
											
							case 'post' : 	switch($view_row['view_table_name']){
							
												case $wpdb->prefix."postmeta" : $jointbl=gettableObject($wpdb->prefix."posts", $tablename);
																				 	$whereCondition.="AND ".$tbl.".post_id  = %s "; 
																					$whereValues[]=$jointbl."ID" ;																
																				 break;
																					 
												case $wpdb->prefix."posts"    : $whereCondition.="AND ".$tbl.".post_parent  = %d ";
																				$whereValues[]=0;
																				 if($editview->view_field=='post'){
																					 $whereCondition.="AND ".$tbl.".post_type  = %s ";
																					 $whereValues[]='post';
																				 }
																				 $selectFields.=$tbl.".ID ,";
																				 break;
																					 
												case $wpdb->prefix."terms"    : $jointbl=gettableObject($wpdb->prefix."posts", $tablename);
																				$fromTables[]=$wpdb->prefix."term_relationships  tr ";
																				$fromTables[]=$wpdb->prefix."term_taxonomy   tt ";
																				if(!empty($jointbl)){
																					$whereCondition.="AND tr.object_id = %s ";
																					$whereValues[]=$jointbl."ID";
																					$groupFields.="GROUP BY ".$jointbl."ID";
																				}// end of if(!empty($jointbl)){
																				$whereCondition.="AND tr.term_taxonomy_id = %s ";
																				$whereValues[]='tt.term_taxonomy_id';
																				$whereCondition.="AND tt.taxonomy = %s ";																				
																				$whereValues[]='post_tag';
																				$whereCondition.="AND ".$tbl.".term_id = %s ";
																				$whereValues[]='tt.term_taxonomy_id';
																				break;									 
											}// end of switch($view_row['view_table_name']){
																						
											break;					
											
							case 'comment': $whereCondition.="AND ".$tbl.".comment_parent  = %d ";
										    $whereValues[]=0;
											$selectFields.=$tbl.".comment_ID, ";
											$selectFields.=$tbl.".comment_post_ID, ";					                
											break;							
						}// end of switch($editview->view_field){												
						
					}// end of if(!in_array($view_row['view_table_name'], $tablename)){
															
					$selectFields.=$tbl.'.'.$view_row['view_field_name'].' , ';
					
					if(!empty($view_row['view_filter_value']) && !empty($view_row['view_filter_condition'])){
										
					switch($view_row['view_filter_condition']){
					
						case 'equalto'         : switch($view_row['search_case']){
												 	case '0' : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." = %s "; 																
													           $whereValues[]=$view_row['view_filter_value'];
													           break;
													case '1' : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." LIKE ( %d ) ";           											
													           $whereValues[]=$view_row['view_filter_value'];
															   break;
												 }// end of switch($view_row['search_case']){
												 
												 break;	
												 
						case 'notequalto'      : switch($view_row['search_case']){
												 	case '0' : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." <> %s ";															
													           $whereValues[]=$view_row['view_filter_value'];
													           break;
													case '1' : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." NOT LIKE ( %s ) "; 												
													           $whereValues[]=$view_row['view_filter_value'];
															   break;
												 }// end of switch($view_row['search_case']){
												
												 break;	
												 
						case 'contains'        : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." LIKE ( %s ) "; 
												 $whereValues[]='%'.$view_row['view_filter_value'].'%';
												 break;	
						
						case 'containany'      : $Postkeywords=$view_row['view_filter_value'];
													$sepraters=array('_', ' ', '.', '(', ')', '&', '+', 'and', 'AND', '-', '|', '\'', '/', '*', '@', '!', '$', '$', '%', '^', '=', '`', '~', '?', '<', '>');
													foreach($sepraters as $symbol){
														$Postkeywords=str_replace($symbol, ',' , $Postkeywords);
													}// end of foreach($sepraters as $symbol){
													$keyArray=explode(',', $Postkeywords);
													$uniquekeys=array_unique($keyArray);	
													$finalKeys=remove_array_empty_values($uniquekeys, true);
													
													$whereCondition.=" AND ( ";																																																				
													$j=0;
													foreach($finalKeys as $key){														
														if($j<count($finalKeys)-1){
															$whereCondition.=$tbl.".".$view_row['view_field_name']." LIKE ( %s ) OR ";
															$whereValues[]='%'.$key.'%';
														}else{
															$whereCondition.=$tbl.".".$view_row['view_field_name']." LIKE ( %s ) ";
															$whereValues[]='%'.$key.'%';
														}
														$j=$j+1;
													}// end of foreach($finalKeys as $key){
													
													$whereCondition.=" ) ";
						
												 break;	
												 
						case 'containall'      : $Postkeywords=$view_row['view_filter_value'];
													$sepraters=array('_', ' ', '.', '(', ')', '&', '+', 'and', 'AND', '-', '|', '\'', '/', '*', '@', '!', '$', '$', '%', '^', '=', '`', '~', '?', '<', '>');
													foreach($sepraters as $symbol){
														$Postkeywords=str_replace($symbol, ',' , $Postkeywords);
													}// end of foreach($sepraters as $symbol){
													$keyArray=explode(',', $Postkeywords);
													$uniquekeys=array_unique($keyArray);	
													$finalKeys=remove_array_empty_values($uniquekeys, true);
													
													$whereCondition.=" AND ( ";																																																				
													$j=0;
													foreach($finalKeys as $key){														
														if($j<count($finalKeys)-1){
															$whereCondition.=$tbl.".".$view_row['view_field_name']." LIKE ( %s ) AND ";
															$whereValues[]='%'.$key.'%';
														}else{
															$whereCondition.=$tbl.".".$view_row['view_field_name']." LIKE ( %s ) ";
															$whereValues[]='%'.$key.'%';
														}
														$j=$j+1;
													}// end of foreach($finalKeys as $key){
													
													$whereCondition.=" ) ";
						
												 break;	
						case 'startswith'      : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." LIKE ( %s ) "; 
												 $whereValues[]=$view_row['view_filter_value'].'%';
												 break;
						case 'endswith'        : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." LIKE ( %s ) "; 
												 $whereValues[]='%'.$view_row['view_filter_value'];
												 break;
						case 'notcontain'      : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." NOT LIKE ( %s ) ";
												 $whereValues[]= '%'.$view_row['view_filter_value'].'%';
												 break;					
						case 'dateequalto'     : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." LIKE ( %s ) "; 
						                         $whereValues[]= '%'.$view_row['view_filter_value'].'%';
												 break;																		
						case 'datenotequalto'  : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." NOT LIKE ( %s ) "; 
						                         $whereValues[]= '%'.$view_row['view_filter_value'].'%';
												 break;																		
						case 'dategreaterthan' : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." > %s "; 
												 $whereValues[]=$view_row['view_filter_value'];
												 break;
						case 'dategreaterequalto' : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." >= %s "; 
												    $whereValues[]=$view_row['view_filter_value'];
													break;
						case 'datelessequalto'    : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." <= %s "; 
						                            $whereValues[]=$view_row['view_filter_value'];
													break;
						case 'datelessthan'    : $whereCondition.="AND ".$tbl.".".$view_row['view_field_name']." < %s "; 
												 $whereValues[]=$view_row['view_filter_value'];
												 break;
						
					}// end of switch($view_row['view_filter_condition']){	
				  }// end of if(!empty($view_row['view_filter_value']) && !empty($view_row['view_filter_condition'])){					  				  
				  																							
				}// end of while($view_row=mysql_fetch_assoc($view_query)){
				
				$generatedQuery= $viewQuery.substr($selectFields,0,-2).' FROM '.implode(",",$fromTables).$whereCondition." ".$groupFields ;
				
				$generatedQuery='"'.$generatedQuery.'":'.implode(", ", $whereValues);
				
				if(!empty($generatedQuery)){
					return $generatedQuery;
				}
				
			}// end of if(mysql_num_rows($view_query)>0){
			else echo "View details are not found!";
		}// end of if(!empty($theid)){
		else echo "View details are not found!";
				
	
	}// end of function generateQuery($theid){
	
	
function gettableObject($tblname, $tblarr){
	$tblkey="";
	$tblkey=array_search($tblname, $tblarr);
	if(!empty($tblkey)){
		return "tbl".$tblkey.".";
	}
	
}// end of function gettableObject($tblname){
	
?>
