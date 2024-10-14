<?php
ob_start();
session_start();
include('db.php');

/*  To get the PDF Format*/
$doc_id = pg_escape_string(strip_tags(trim($_REQUEST['doc_id'])));
	$query_doc = "select action_doc_id, encode(action_doc_content,'escape') action_doc_content,action_doc_name,action_doc_type from pet_action_doc where action_doc_id='".$doc_id."'";
	$fetch_doc = $db->query($query_doc);
	$row = $fetch_doc->fetch(PDO::FETCH_BOTH);
	$doctype = $row['action_doc_type'];
	$docname = preg_replace('/\s+/', '', $row['action_doc_name']);
	header("Content-type: $doctype");
	header("Content-Disposition: attachment; filename=$docname");
	echo $doc_content = pg_unescape_bytea($row['action_doc_content']);

/* End of PDF Format*/

exit;

?>