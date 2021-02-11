<?php

    //* Example
    // $url = 'https://www.youtube.com/watch?v=A3eWSytd8og';
    // $video = new YouTubeIframe;
    // $video->SirkaVidea = 720;
    // echo $video->getYouTubeIframe($url);

Class YouTubeIframe {

    public $SirkaVidea = 560;
    
    private function getYouTubeEmbeddedURL($url) {
        return "https://www.youtube.com/embed/" . $this->getYouTubeID($url);
    }
    
    private function getYouTubeID($url) {
        $queryString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $params);
        if (isset($params['v']) && strlen($params['v']) > 0) {
            return $params['v'];
        } else {
            return false;
        }
    }

    private function VyskaVidea(){
        //nastaví pomer strán k sírke 4:3
        return ceil(($this->SirkaVidea/4)*3);
    }

    public function getYouTubeIframe($url){
        if ($this->getYouTubeID($url)) {
            return '<iframe width="'.$this->SirkaVidea.'" height="'.$this->VyskaVidea().'" src="'. $this->getYouTubeEmbeddedURL($url).'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } else {
            return "";
        }
    }

}