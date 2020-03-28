<?php
include_once('../common/include.php');
$conn=getConnection();

if($conn==null){
    sendResponse(500,$conn,'Server Connection Error');
}else{
    $sql = "SELECT `monthlyversetitle`, `monthlyversesecondtitle`, `monthlyverse`, `liveyoutubechannel`, `choirtitle`, `choirvideo`, `singingtitle`, `singingvideo`, `phone`, `email`, `address` FROM `mainsetting`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $settings=array();
        while($row = $result->fetch_assoc()) {
            $setting=array(
                "monthlyversetitle" => $row["monthlyversetitle"],
                "monthlyversesecondtitle" => $row["monthlyversesecondtitle"],
                "monthlyverse" => $row["monthlyverse"],
                "liveyoutubechannel" => $row["liveyoutubechannel"],
                "choirtitle" => $row["choirtitle"],
                "choirvideo" => $row["choirvideo"],
                "singingtitle" => $row["singingtitle"],
                "singingvideo" => $row["singingvideo"],
                "phone" => $row["phone"],
                "email" => $row["email"],
                "address" => $row["address"]
            );
            array_push($settings,$setting);
        }
        sendResponse(200,$settings,'setting List');
    } else {
        sendResponse(404,$result,'setting not available');
    }
    $result->free_result();
    $t_id = $conn->thread_id;
    $conn -> kill($t_id);
    $conn->close();
}
?>
