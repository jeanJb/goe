<?php
session_start();
session_destroy();
//DESCONEXION
header("Location:../index.html");
exit;
?>