<?php
    /**
     * Created by PhpStorm.
     * User: Sebbans
     * Date: 2018-06-15
     * Time: 19:07
     */



    class SelfMade{

        const defaultToken = ":defaulttoken:";
        const defaultUsername = "SebbeJohansson";
        const defaultURL = "https://api.github.com/users/SebbeJohansson/events";

        private $curl = null;
        public $url = null;
        public $debugmode = false;

        public $xml = "";

        public $events = Array();
        public $commits = Array();

        public $response = array('successful' => false, 'errors' => array(), 'statusmessage' => "", 'variables' => array());

        function __construct($username, $personalaccesstoken, $url = SelfMade::defaultURL) {

            if($url == ""){
                $this->response['errors'][] = "Api URL not set - Defaulting to ".SelfMade::defaultURL.".";
                $this->url = SelfMade::defaultURL;
            }
            if($personalaccesstoken == ""){
                $this->response['errors'][] = "Token not set - Defaulting to ".SelfMade::defaultToken.".";
                $personalaccesstoken = SelfMade::defaultToken;
            }
            if($username == ""){
                $this->response['errors'][] = "Username not set - Defaulting to ".SelfMade::defaultUsername.".";
                $username = SelfMade::defaultUsername;
            }

            $this->curl = $curl = curl_init("curl -u $username:$personalaccesstoken https://api.github.com/user");

            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', "User-Agent: https://api.github.com/user", "Authorization: token $personalaccesstoken"));

            $content = curl_exec($curl);
            if($this->debugmode){
                $this->response['variables']['content'] = $content;
            }
            $this->events = json_decode($content,TRUE);

            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($this->debugmode){
                $this->response['variables']['http_status'] = $http_status;
            }

        }
        function __destruct() {
            curl_close($this->curl);
        }

        private function array_depth(array $array) {
            $max_depth = 1;

            foreach ($array as $value) {
                if (is_array($value)) {
                    $depth = $this->array_depth($value) + 1;

                    if ($depth > $max_depth) {
                        $max_depth = $depth;
                    }
                }
            }

            return $max_depth;
        }

        public function SetEvents($url = SelfMade::defaultURL){

            curl_setopt($this->curl, CURLOPT_URL, $url);

            $content = curl_exec($this->curl);
            if($this->debugmode){
                $this->response['variables']['purecontent'] = print_r($content, $this->debugmode);
            }
            $this->events = json_decode($content,TRUE);

            $http_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
            if($this->debugmode){
                $this->response['errors'][] = "curlstatus : ".$http_status;
            }

            if(!isset($this->events) || $this->events == NULL || empty($this->events)){
                $this->response['errors'][] = "No events. Error. Terminating.";
            }
        }

        public function GetWatchEvents(){
            $watchevents = Array();
            foreach($this->events as $key=>$event){
                if($event['type'] == 'WatchEvent'){
                    $watchevents[] = $event;
                }
            }
            return $watchevents;
        }
        public function GetIssueEvents(){
            $issueEvents = Array();
            foreach($this->events as $key=>$event){
                if($event['type'] == 'IssuesEvent'){
                    $issueEvents[] = $event;
                }
            }
            return $issueEvents;
        }
        public function GetPushEvents(){
            $pushEvents = Array();
            foreach($this->events as $key=>$event){
                if($event['type'] == 'PushEvent'){
                    $pushEvents[] = $event;
                }
            }
            return $pushEvents;
        }
        public function GetCreateEvents(){
            $createEvents = Array();
            foreach($this->events as $key=>$event){
                if($event['type'] == 'CreateEvent'){
                    $createEvents[] = $event;
                }
            }
            return $createEvents;
        }
        public function GetForkEvents(){
            $forkEvents = Array();
            foreach($this->events as $key=>$event){
                if($event['type'] == 'ForkEvent'){
                    $forkEvents[] = $event;
                }
            }
            return $forkEvents;
        }

        public function FormatEvent($event, $newauthor = true, $newday = true){
            (isset($event['created_at'])) ? $createdOn = new DateTime($event['created_at']) : $createdOn = new DateTime($event['commit']['committer']['date']);
            (isset($event['author']['avatar_url'])) ? $authorAvatar = $event['author']['avatar_url'] : $authorAvatar = $event['actor']['avatar_url'];
            (isset($event['committer']['login'])) ? $authorName = $event['committer']['login'] : $authorName = $event['actor']['display_login'];
            (isset($event['commit']['message'])) ? $message = $event['commit']['message'] : $message = $event['type'];



            $html = "<section class='pl-sm-5 p-2'>";
            //$html .= "<section class='row justify-content-center'>";
            //$html .= "<section class='col-lg-4 bg-light'>";

            /*if($newday){
                //$html .= "</section>";
                //$html .= "<section class='col-lg-4 bg-light'>";
                $html .= "<section class='mx-2'>".$createdOn->format('Y F d')."</section>";
                $newauthor = true;
            }*/

            if($newauthor == true){
                $html .= "<section class='author-info my-1 mb-2 d-flex'>";
                $html .= "<img src='{$authorAvatar}' class='rounded-circle img-thumbnail mx-2' style='width: 50px; height: 50px;'>";
                $html .= "<section class='d-flex align-self-center'>{$authorName}</section>";
                $html .= "</section>";
            }

            $html .= "<section class='commit-info pl-sm-5'>";
            $html .= "<section class='d-sm-inline-flex mr-2'>";
            $html .= "{$createdOn->format('H:i')}";
            $html .= "</section>";

            $html .= "<section class='d-sm-inline-flex' style='white-space: pre-line; max-width: 90%;'>";
            $html .= "$message</br>";
            if(isset($event['payload']['commits'][0]['message'])){
                $html .= $event['payload']['commits'][0]['message'];
            }
            $html .= "</section>";
            $html .= "</section>";



            //$html .= "</section>";
            $html .= "</section>"; // closing commit box.
            return $html;
        }

        public function GetGithubFeed() {
            global $selfmade;
            if($selfmade->events == NULL){
                $selfmade->response['statusmessage'] = "No events for github api url.";

                return "Wow no events";
            }
            $lastauthor = null;
            $lastday = null;
            $newday = null;
            $newauthor = null;

            $html = "";

            $html .= "<section class='container justify-content-center'>";
            $html .= "<section class='row'>";
            //$selfmade->response['variables']['pureevents'] = $selfmade->events;
            if (isset($selfmade->events['message'])) {
                if ($selfmade->events['message'] == "Not Found") {
                    $selfmade->response['errors'][] = "No events found.";
                    $selfmade->response['successful'] = false;
                    $selfmade->response['statusmessage'] = "No Events Found.";
                }
            } else {
                if($this->array_depth($selfmade->events) > 2){
                    foreach ($selfmade->events as $event) {
                        (isset($event['created_at'])) ? $createdOn = new DateTime($event['created_at']) : $createdOn = new DateTime($event['commit']['committer']['date']);

                        $newday = !($createdOn->format('Y:m:d') . "" == $lastday);
                        (isset($event['committer']['login'])) ? $newauthor = !($lastauthor == $event['committer']['login']) : $newauthor = !($lastauthor == $event['actor']['display_login']);

                        if ($newday) {
                            if (isset($lastday)) {
                                $html .= "</section>"; // Closes newDay block.
                            }
                            $html .= "<section class='col-12 pb-3 bg-light newday'>"; // Starts New Day Block

                            $html .= "<section class='my-2 h4 font-weight-bold'>" . $createdOn->format('Y F d') . "</section>";
                            $newauthor = true;

                        }


                        $html .= $selfmade->FormatEvent($event, $newauthor, $newday);
                        (isset($event['committer']['login'])) ? $lastauthor = $event['committer']['login'] : $lastauthor = $event['actor']['display_login'];
                        $lastday = $createdOn->format('Y:m:d');
                        $selfmade->response['successful'] = true;
                    }
                    $selfmade->response['successful'] = true;
                }else{
                    $selfmade->response['errors'][] = "No events found.";
                    $selfmade->response['successful'] = false;
                    $selfmade->response['statusmessage'] = "No Events Found.";
                }
            }
            $html .= "</section>"; // Closing row
            $html .= "</section>"; // Closing container
            return $html;
        }

    }
