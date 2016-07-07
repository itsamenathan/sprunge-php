<?php
include("conf.php");

$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);

if (empty($_GET) and empty($_POST)) {
  include "home.php";
}

if ( !empty($_GET) ) {
  if($_GET['id']){
    $hash = $mysqli->real_escape_string($_GET['id']);
    $result = $mysqli->query("SELECT data FROM post where hash = '$hash'");
    $row = $result->fetch_assoc();
    $data = htmlspecialchars($row["data"]);
    include "id.php";
  }
}

if ( !empty($_POST) ) {
  if($_POST['sprunge']){
    $data = $mysqli->real_escape_string($_POST['sprunge']);
    $hash = hash("crc32b", $data, false); 
    $mysqli->real_query("SELECT hash FROM post where hash = $hash");
    if($mysqli->field_count === 0){
      $mysqli->real_query("INSERT INTO post (hash,data) VALUES ('$hash', '$data')");
      echo "https://frcv.net/pastebin/?id=$hash\n";
    }
    else{
      echo "Hmm, there was a problem.  Please try again!";
    }
  }
}
?>
