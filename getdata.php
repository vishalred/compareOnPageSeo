<?php

if(isset($_POST['submit'])) {

  $file_upload = $_FILES['file-input1']['tmp_name'];
  $title = date('YmdHis');
  $path = $_SERVER['DOCUMENT_ROOT'] . "/meta_comparision/files/".$title.".csv";
  $target_file = $path . $_FILES['file-input1']['name'];
  move_uploaded_file ($file_upload, $target_file);

  if (file_exists ($target_file)) {
    file_put_contents ('check.txt', $target_file . " - " . $path);
    echo "Wait till cron run and visit to the url : ". $_SERVER['SERVER_NAME']."/meta_comparision/files/";
  }
}
