<?php
set_time_limit(0);

if(file_exists('check.txt')) {
  $content = file_get_contents('check.txt',FILE_USE_INCLUDE_PATH);
  $data_exp = explode("-", $content);
  $path = trim($data_exp[1]);
  $url = trim($data_exp[0]);
  $title = date('YmdHis');
  $file = fopen($path, 'w');
  fputcsv($file, array('URL', 'Meta Title', 'Meta Description',));
  $row = 1;
  if (($handle = fopen($url, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
      $num = count($data);
      //echo "<p> $num fields in line $row: <br /></p>\n";
      $row++;
      for ($c=0; $c < $num; $c++) {
        $datas =  $data[$c];
        $data_content = get_meta_data(trim($datas));
        $exp = explode("$", $data_content);
        $titles =  $exp[0];
        $desc = $exp[1];
        fputcsv($file, array($datas ,$titles, $desc));
      }
    }
    fclose($file);
    fclose($handle);
    unlink($url);
  }
}
unlink('check.txt');

function file_get_contents_curl($url) {
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      $data = curl_exec($ch);
      curl_close($ch);

      return $data;
}

function get_meta_data($data_url) {
  $html = file_get_contents_curl($data_url);
  $doc = new DOMDocument();
  @$doc->loadHTML($html);
  $nodes = $doc->getElementsByTagName('title');

  $title = $nodes->item(0)->nodeValue;
  $metas = $doc->getElementsByTagName('meta');
  $description = '';
  for ($i = 0; $i < $metas->length; $i++) {
    $meta = $metas->item($i);
    if($meta->getAttribute('name') == 'description') {
      $description = $meta->getAttribute('content');
    }
  }
  $content = $title . " $ ". $description;

  return $content;
}



