

<?php
    require_once("includes/classes/SelfMade.php");


?>

<head>
    <?php
        require_once("includes/views/head.php");
    ?>
    <link href='includes/css/commits.css' type="text/css" rel='stylesheet' />
    <script src="includes/js/script.js"></script>
</head>


<html>
<body>

<?php

    global $selfmade;
    $selfmade = new SelfMade(SelfMade::defaultUsername, SelfMade::defaultToken, "https://api.github.com/repos/fakedy/DeltaCity/commits");
    $selfmade->debugmode = false;

    $selfmade->SetEvents("https://api.github.com/repos/fakedy/DeltaCity/commits");



?>
<section class="container-fluid">
    <section class="row justify-content-center">
        <section class="col-lg-4 col-sm-6 col-12 my-4">
            <form method='post' id='feedform' enctype='multipart/form-data'>
                <section class="form-group">
                    <label for="username">Github Username:</label>u
                    <input type='text' name='username' id='username' placeholder='Username' class="form-control">
                </section>

                <section class="form-group">
                    <label for="token">Personal Access Token (or other token):</label>
                    <input type='text' name='token' id='token' placeholder='PAT' class="form-control">
                </section>

                <section class="form-group">
                    <label for="apiurl">Github API url:</label>
                    <input type='text' name='apiurl' id='apiurl' placeholder='https://api.github.com/users/SebbeJohansson/events' class="form-control">
                </section>

                <input type='hidden' name='action' value='githubfeedcreator'>

                <button type='submit' name='githubfeedcreator' class="btn btn-primary">Create Feed</button>
            </form>
        </section>
    </section>
</section>


<section id="githubfeed">
</section>

</body>
</html>