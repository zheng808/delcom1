<?php
$valid = true;
if ($_FILES["fileToUpload"]["error"] > 0)
{
  $valid = false;
  $error  = $_FILES["fileToUpload"]["error"];
  $response = array('success' => false, 'msg' => $error);
  echo json_encode($response);
}

$msg = '';
$target_dir = '../uploads/';
$workorder_id = $_POST["workorderId"];

$target_file = $target_dir . $workorder_id .'_' . basename($_FILES["fileToUpload"]["name"]);
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$file_size = round($_FILES["fileToUpload"]["size"] / 1024, 2) . "  Kilo Bytes";
$file_name = $workorder_id .'_' . $_FILES["fileToUpload"]["name"];



// Check if file already exists
if (file_exists($target_file)) {
    $msg = "Replacing File, ";

}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 2097152) {
    $valid = false;

    $msg = "Sorry, your file is too large.";
    $response = array('success' => false, 'msg' => $msg);
    echo json_encode($response);
}

// Allow certain file formats
if($fileType != "pdf" ) {
    $valid = false;

    $msg = "Sorry, only PDF files are allowed.";
    $response = array('success' => false, 'msg' => $msg);
    echo json_encode($response);
}

    
// if everything is ok, try to upload file
if ($valid) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $msg = $msg." File '".$file_name."' uploaded successfully";
        $response = array('success' => true, 
        'data' => array('name' => $file_name, 'size' => $file_size),
        'msg' => $msg);
        echo json_encode($response);
    } else {
        $msg = $msg." Sorry, there was an error uploading ".$file_name;
        $response = array('success' => false,
            'data' => array('name' => $file_name, 'size' => $file_size),
            'msg' => $msg);
        echo json_encode($response);
    }
}


?>