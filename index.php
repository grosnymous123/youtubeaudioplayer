<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if($_SERVER['REQUEST_URI'] == "/" || strlen($_SERVER['REQUEST_URI']) < 8){
    include($_SERVER['DOCUMENT_ROOT'] . 'home.html');
}
else{
    $f = parse_url($_SERVER['REQUEST_URI']);
    $urlP = [];
    parse_str($f['query'], $urlP);
    $id = "";
    if(isset($urlP['v'])){
        $id .= $urlP[v];
    }
    else if(preg_match('/youtu.be\/(.+)/', $_SERVER['REQUEST_URI']))
    {
        $id .= preg_replace('/(.+)youtu.be\/(.+)/', '$2', $_SERVER['REQUEST_URI']);
    }
    else if(preg_match('/([a-zA-Z0-9]+)/', $_SERVER['REQUEST_URI']))
    {
        $id .= preg_replace('/\/([a-zA-Z0-9]+)/', '$1', $_SERVER['REQUEST_URI']);
    }
    else{
        $id .= $_REQUEST['REQUEST_URI'];
    }
    if($id != ""){
        $ch = curl_init("https://www.youtube.com/get_video_info?html5=1&video_id=" . $id);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        $a = urldecode(curl_exec($ch));
        $a = preg_replace("/&url=/", "&url[]=",$a);
        $b = [];
        parse_str($a, $b);
        curl_close($ch);
        $u = "";
        for($i = 0; $i < count($b['url']); $i++){
            $z = [];
            parse_str($b['url'][$i], $z);
            if($z['mime'] == 'audio/webm'){
                $u = $b['url'][$i];
                break;
            }
        }
        if($u != ""){
            header("Location: " . $u);
        }
        else{
            echo "Error 404, this file doesn't exist or youtube is blocking it.";
            http_response_code(404);            
        }
    }
    else{
    include($_SERVER['DOCUMENT_ROOT'] . 'home.html');
    }
}
?>









