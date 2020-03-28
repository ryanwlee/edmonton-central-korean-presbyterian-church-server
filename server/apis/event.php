<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `listorder`, `content` FROM `events`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $events=array();
        while($row = $result->fetch_assoc()) {
            $event=array(
                "listorder" => $row["listorder"],
                "content" => $row["content"]
            );
            array_push($events,$event);
        }
        sendResponse(200,$events,'events List');
    } else {
        sendResponse(404,$result,'events not available');
    }
    $result->free_result();
    $t_id = $conn->thread_id;
    $conn -> kill($t_id);
    $conn->close();
}
?>
