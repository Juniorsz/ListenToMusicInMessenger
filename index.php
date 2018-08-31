<?php
    if(isset($_GET['song']))
    {
        $song = $_GET['song'];
        $apiKey = 'YOUR_GOOGLE_API_KEY';
        $encode = urlencode(str_replace(' ','+',$song));
        $searchSong = json_decode(request("https://www.googleapis.com/youtube/v3/search?part=snippet&q=$encode&key=$apiKey"),TRUE);
        if($searchSong['items'])
        {
            $songId = $searchSong['items'][0]['id']['videoId'];
            $getAudio = $getAudio = json_decode(request("http://josemicoronil.hol.es/youtube_get_video_url.php?url=https://www.youtube.com/watch?v=$songId"),TRUE);
            if($getAudio[2]['url'])
            {
                echo jsonData($getAudio[2]['url'],$searchSong['items'][0]['snippet']['title']);
            }
        }
    }
    function request($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HEADER => 0
        ]);
        return $response = curl_exec($ch);
    }
    function jsonData($urlSong,$song)
    {
        $arr = array (
            'messages' =>
             array (
                0 =>
                array(
                    'attachment' => 
                     array(
                         'type' => 'audio',
                         'payload' => 
                          array(
                             'url' => $urlSong,
                             'text' => $song
                          )
                     )
                )
            ),
        );
        return json_encode($arr);
    }
?>

