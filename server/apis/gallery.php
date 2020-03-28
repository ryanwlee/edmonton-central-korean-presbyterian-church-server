<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `listorder`, `src` FROM `gallery`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $pics=array();
        while($row = $result->fetch_assoc()) {
            $pic=array(
                "listorder" => $row["listorder"],
                "src" => $row["src"]
            );
            array_push($pics,$pic);
        }
        sendResponse(200,$pics,'pics List');
    } else {
        sendResponse(404,$result,'pics not available');
    }
    $result->free_result();
    $t_id = $conn->thread_id;
    $conn -> kill($t_id);
    $conn->close();
}
?>
