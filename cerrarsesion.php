<?php
session_start();
session_destroy();
header('Location:login.php');
exit();
?>

<script type="text/javascript">
    history.replaceState({},'','login.php');
    history.pushState(null,'','login.php');
    window.onpopstate= function(){
        window.location.href='login.php';
    };

</script>
