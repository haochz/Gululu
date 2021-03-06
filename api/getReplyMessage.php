<?php
/**
 * http://localhost/db_nextdoor/api/getReplyMessage?mid=2
 */
require_once 'api.php';
class getReplyMessage extends api {

	public function doExecute() {
		// in case of sql injection
		$mid = intval($_GET['mid']);
		$isUnRead = intval($_GET['unread']);
		$replyMessage = '';

		if ($isUnRead === 1){
			//unread replys
			$replyMessage = 'SELECT u.firstname, u.lastname, u.photo, r.* FROM receive_reply rr, reply r, user u, message m WHERE u.uid = r.uid AND m.mid = r.mid AND m.uid = rr.uid AND rr.rid = r.rid AND is_read = 0 AND r.mid = ' .$mid . ';';
		} else{
			//all replys
			$replyMessage = 'SELECT u.firstname, u.lastname, u.photo, r.* FROM receive_reply rr, reply r, user u, message m WHERE u.uid = r.uid AND m.mid = r.mid AND m.uid = rr.uid AND rr.rid = r.rid AND r.mid = ' .$mid . ';';
		}
		
		$query = mysqli_query($this->conn, $replyMessage);
		$data = mysqli_fetch_all($query, MYSQLI_ASSOC);

		// if ($data) {
		return $data;
		// } else {
		// 	throw new Exception("No reply message.");
		// }
	}
}

try {
	$thisClass = new getReplyMessage;
	$thisClass->res['data'] = $thisClass->doExecute();

} catch (Exception $e) {
	$thisClass->res['status'] = $e->getCode() ? $e->getCode() : -1;
	$thisClass->res['message'] = $e->getMessage();

} finally {
	echo json_encode($thisClass->res);
}


?>