<?php

    require_once("classes/SelfMade.php");
    $url = $_POST['apiurl'];
    $token = $_POST['token'];
    $username = $_POST['username'];

    if($_POST['apiurl'] == ""){
        $url = SelfMade::defaultURL;
    }
    if($_POST['token'] == ""){
        $token = SelfMade::defaultToken;
    }
    if($_POST['username'] == ""){
        $username = SelfMade::defaultUsername;
    }

    global $selfmade;
    $selfmade = new SelfMade($username, $token, $url);
    $selfmade->debugmode = false;

    if($_POST['apiurl'] == ""){
        $selfmade->response['errors'][] = "Api URL not set";
    }
    if($_POST['token'] == ""){
        $selfmade->response['errors'][] = "Token not set";
    }
    if($_POST['username'] == ""){
        $selfmade->response['errors'][] = "Username not set";
    }


    if($selfmade->debugmode){
    }
    $selfmade->response['variables']['apiurl'] = $url;
    $selfmade->response['variables']['token'] = $token;
    $selfmade->response['variables']['username'] = $username;

    if (isset($_POST['action'])){
        $action = $_POST['action'];

        switch ($action){
            case 'githubfeedcreator':

                $selfmade->SetEvents();
                $selfmade->response['variables']['output'] = $selfmade->GetGithubFeed();
                break;
            default:
                break;
        }
    }else{

    }
    echo json_encode($selfmade->response);
