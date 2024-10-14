<?php
ob_start();
session_start();
?>
<?php
include('db.php');

/*  To get the PDF Format*/
$doc_id = pg_escape_string(strip_tags(trim($_REQUEST['doc_id'])));
$source = pg_escape_string(strip_tags(trim($_REQUEST['source'])));
	if ($source == 'P' || $source == '') {
	$query_doc = "select doc_id, encode(doc_content,'escape') doc_content,doc_name,doc_type from pet_master_doc where doc_id='".$doc_id."'";
	} else {
		$query_doc = "select action_doc_id, encode(action_doc_content,'escape') doc_content,action_doc_name doc_name ,action_doc_type doc_type from pet_action_doc where action_doc_id='".$doc_id."'";
	}
	$fetch_doc = $db->query($query_doc);
	$row = $fetch_doc->fetch(PDO::FETCH_BOTH);
	$doctype = $row['doc_type'];
	$docname = preg_replace('/\s+/', '', $row['doc_name']);
	header("Content-type: $doctype");
	header("Content-Disposition: attachment; filename=$docname");
	echo $doc_content = pg_unescape_bytea($row['doc_content']);

/* End of PDF Format*/

exit;

?>