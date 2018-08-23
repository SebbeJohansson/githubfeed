<?php

    require_once("classes/SelfMade.php");
    $url = $_POST['apiurl'];
    $token = $_POST['token'];
    $username = $_POST['username'];

    global $selfmade;
    $selfmade = new SelfMade($username, $token, $url);
    $selfmade->debugmode = false;

    if($selfmade->debugmode){
        $selfmade->response['variables']['apiurl'] = $selfmade->url;
        $selfmade->response['variables']['token'] = $token;
        $selfmade->response['variables']['username'] = $username;
    }

    if (isset($_POST['action'])){
        $action = $_POST['action'];

        switch ($action){
            case 'githubfeedcreator':
                $selfmade->SetEvents($selfmade->url);
                $selfmade->response['variables']['output'] = $selfmade->GetGithubFeed();
                break;
            default:
                break;
        }
    }else{

    }
    echo json_encode($selfmade->response);
