<?php

namespace App\Service;

use App\Domain\Model\Video;

class IframeBuilder
{
    /**
     * @param Video $video
     */
    public function analyseUrl(Video $video)
    {
        $url = $video->getUrl();  // on récupère l’url de la video issu du formulaire

        if (preg_match("#iframe#", $url))
        {
            if (preg_match("#youtube#", $url))
            {
                $this->youtubeIframe($video);
            }
            else if((preg_match("#dailymotion#", $url)))
            {
                $this->dailymotionIframe($video);
            }
            else if((preg_match("#vimeo#", $url)))
            {
                $this->vimeoIframe($video);
            }
        }
        else
        {
            if (preg_match("#youtube.com#", $url))
            {
                $this->youtubeId($video);
            }
            else if((preg_match("#dailymotion.com#", $url)))
            {
                $this->dailymotionId($video);
            }
            else if((preg_match("#vimeo.com#", $url)))
            {
                $this->vimeoId($video);
            }
        }
    }

    /**
     * @param Video $video
     */
    public function youtubeId(Video $video)
    {
        //ex : https://www.youtube.com/watch?v=92rXe1XJMuI
        $tableaux = explode("=", $video->getUrl());

        $video->setIdentif($tableaux[1]);
        $video->setType('youtube');
    }

    /**
     * @param Video $video
     */
    private function youtubeIframe(Video $video)
    {
        //ex :  <iframe width="560" height="315"
        //      src="https://www.youtube.com/embed/92rXe1XJMuI"
        //      frameborder="0" allowfullscreen></iframe>
        $tableau1 = explode("embed/", $video->getUrl());
        $tableau2 = explode("\"", $tableau1[1]);

        $video->setIdentif($tableau2[0]);
        $video->setType('youtube');
    }

    /**
     * @param Video $video
     */
    private function dailymotionId(Video $video)
    {
        // ex : http://www.dailymotion.com/video/x4wh6to_la-planete-des-singes-3-video-virale_shortfilms
        $cas = explode("/", $video->getUrl());

        $idb = $cas[4];  // On récupère la 4ème partie qui nous intéresse
        $bp = explode("_", $idb);
        $id = $bp[0];

        $video->setIdentif($id);
        $video->setType('dailymotion');
    }

    /**
     * @param Video $video
     */
    private function dailymotionIframe(Video $video)
    {
        //ex :  <iframe frameborder="0" width="480" height="270"
        //      src="//www.dailymotion.com/embed/video/x38yzyc"
        //      allowfullscreen></iframe>

        $tableau1 = explode("video/", $video->getUrl());
        $tableau2 = explode("\" ", $tableau1[1]);

        $video->setIdentif($tableau2[0]);
        $video->setType('dailymotion');
    }

    /**
     * @param Video $video
     */
    private function vimeoId(Video $video)
    {
        // ex : https://vimeo.com/61350155
        $tableaux = explode("/", $video->getUrl());
        $id = $tableaux[count($tableaux)-1];

        $video->setIdentif($id);
        $video->setType('vimeo');

    }

    /**
     * @param Video $video
     */
    private function vimeoIframe(Video $video)
    {
        //ex :  <iframe src="https://player.vimeo.com/video/45834437?title=0&byline=0&portrait=0"
        //      width="640" height="360" frameborder="0"
        //      webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        if (preg_match("#/?title#", $video->getUrl())){
            $tableau1 = explode("video/", $video->getUrl());
            $tableau2 = explode("?", $tableau1[1]);

            $video->setIdentif($tableau2[0]);
            $video->setType('vimeo');
        }
        else {
            $tableau1 = explode("video/", $video->getUrl());
            $tableau2 = explode("\"", $tableau1[1]);

            $video->setIdentif($tableau2[0]);
            $video->setType('vimeo');
        }
    }
}
