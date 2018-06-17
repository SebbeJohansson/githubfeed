

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

    /*
     *
     * https://github.com/SebbeJohansson.atom
     * http://cjbrock.github.io/blog/2012/11/13/how-to-create-a-github-feed-for-your-website/
     * https://developer.github.com/v3/activity/feeds/
     * https://developer.github.com/v3/#rate-limiting
     *
    */

    global $selfmade;
    $selfmade = new SelfMade(SelfMade::defaultUsername, SelfMade::defaultToken, SelfMade::defaultURL, true);
    $selfmade->debugmode = true;

    include("includes/views/events.php");

?>

</body>
</html>