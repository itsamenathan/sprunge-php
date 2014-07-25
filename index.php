<?php
include("config.php");

$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);

if (empty($_GET) and empty($_POST)) {
echo <<< EOT
<pre>
sprunge(1)                          SPRUNGE                          sprunge(1)

NAME
    sprunge: command line pastebin. Copy of http://sprunge.us

SYNOPSIS
    \<command\> | curl -F 'sprunge=<-' https://frcv.net/pastebin/

DESCRIPTION
    NOTE: THIS IS NOT WORKING RIGHT NOW!!!
    add ?<lang> to resulting url for line numbers and syntax highlighting
    use this form to paste from a browser

EXAMPLES
    ~$ cat bin/ching | curl -F 'sprunge=<-' https://frcv.net/pastebin/
       https://frcv.net/pastebin/?id=12345678

SEE ALSO
    http://github.com/rupa/sprunge
</pre>
EOT;
}

if($_GET['id']){
  $hash = $mysqli->real_escape_string($_GET['id']);
  $result = $mysqli->query("SELECT data FROM post where hash = '$hash'");
  $row = $result->fetch_assoc();
  printf("<pre>%s</pre>",$row["data"]);
}

if ($_POST['sprunge']){
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
?>
