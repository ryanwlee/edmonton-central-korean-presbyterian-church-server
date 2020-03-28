<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `listorder`, `content` FROM `news`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $news=array();
        while($row = $result->fetch_assoc()) {
            $new=array(
                "listorder" => $row["listorder"],
                "content" => $row["content"]
            );
            array_push($news,$new);
        }
        sendResponse(200,$news,'news List');
    } else {
        sendResponse(404,$result,'news not available');
    }
    $result->free_result();
    $t_id = $conn->thread_id;
    $conn -> kill($t_id);
    $conn->close();
}
?>
