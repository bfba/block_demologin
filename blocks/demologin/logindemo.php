<?php
    require_once("../../config.php");
    $SESSION->on = true;
    redirect('logindemo2.php?'.$_SERVER["QUERY_STRING"]);
    exit;
?>