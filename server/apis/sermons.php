<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `sermondate`, `title`, `src`, `content` FROM `sermon`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sermons=array();
        while($row = $result->fetch_assoc()) {
            $sermon=array(
                "sermondate" => $row["sermondate"],
                "title" => $row["title"],
                "src" => $row["src"],
                "content" => $row["content"],
            );
            array_push($sermons,$sermon);
        }
        sendResponse(200,$sermons,'sermon List');
    } else {
        sendResponse(404,$result,'sermon not available');
    }
    $result->free_result();
    $t_id = $conn->thread_id;
    $conn -> kill($t_id);
    $conn->close();
}
?>
