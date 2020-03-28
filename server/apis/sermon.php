<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `secondtitle`, `title`, `src`, `description` FROM `mainsermon`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sermons=array();
        while($row = $result->fetch_assoc()) {
            $sermon=array(
                "secondtitle" => $row["secondtitle"],
                "title" => $row["title"],
                "src" => $row["src"],
                "description" => $row["description"],
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
