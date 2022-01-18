<?php
  function dbconnect(){
  $db = new mysqli('localhost:3306', 'root', 'root', 'trabelboard_db');
  if(!$db){
    die($db->error);
  }
  return $db;
  }

  //htmlspecialcharsを短くする
  function h($value){
   return htmlspecialchars($value, ENT_QUOTES);
  }
?>