<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `jubodate`, `jubotitle`, `page1`, `page2`, `jubopdf` FROM `jubo`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $jubos=array();
        while($row = $result->fetch_assoc()) {
            $jubo=array(
                "jubodate" => $row["jubodate"],
                "jubotitle" => $row["jubotitle"],
                "page1" => $row["page1"],
                "page2" => $row["page2"],
                "jubopdf" => $row["jubopdf"]
            );
            array_push($jubos,$jubo);
        }
        sendResponse(200,$jubos,'Jubo List');
    } else {
        sendResponse(404,$result,'Jubo not available');
    }
    $result->free_result();
    $t_id = $conn->thread_id;
    $conn -> kill($t_id);
    $conn->close();
}
?>
