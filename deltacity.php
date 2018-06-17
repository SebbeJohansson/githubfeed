

<?php
    require_once("includes/classes/SelfMade.php");


?>

<head>
    <?php
        require_once("includes/views/head.php");
    ?>
    <link href='includes/css/commits.css' type="text/css" rel='stylesheet' />

</head>

<html>
<body>

<?php

    global $selfmade;
    $selfmade->debugmode = false;

    $selfmade->SetEvents("https://api.github.com/repos/fakedy/DeltaCity/commits");

    include("includes/views/commits.php");



?>

</body>
</html>
