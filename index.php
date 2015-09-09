<?php
include("conf.php");

$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);

if (empty($_GET) and empty($_POST)) {
echo <<< EOT
<!DOCTYPE html>
<html>
<head>
<pre>
sprunge(1)                          SPRUNGE                          sprunge(1)

NAME
    sprunge: command line pastebin. Copy of http://sprunge.us

SYNOPSIS
    &lt;command&gt; | curl -F 'sprunge=<-' https://frcv.net/pastebin/

DESCRIPTION
    syntax hightlighting by http://highlightjs.org/

EXAMPLES
    ~$ cat bin/ching | curl -F 'sprunge=<-' https://frcv.net/pastebin/
       https://frcv.net/pastebin/?id=12345678

SEE ALSO
    https://github.com/rupa/sprunge
    https://github.com/itsamenathan/sprunge-php
</pre>
</head>
<body>
EOT;
}

if ( !empty($_GET) ) {
  if($_GET['id']){
    $hash = $mysqli->real_escape_string($_GET['id']);
    $result = $mysqli->query("SELECT data FROM post where hash = '$hash'");
    $row = $result->fetch_assoc();
echo <<< EOT
<!DOCTYPE html>
<html>
<head>
<style>
body {
    background-color: #f8f8f8;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/styles/github.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
EOT;

    printf("<pre><code>%s</code></pre>",$row["data"]);

echo <<< EOT
</head>
<body>
EOT;
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
