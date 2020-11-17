<?php


namespace Core\Factory;

use Core\Downloader;
use Core\Face\SiteImageLoader;
use DOMDocument;

class DefaultLoader implements SiteImageLoader
{
    /**
     * String with link address
     * @var string
     */
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function downloadImages(): bool
    {
        $count = 1;
        $htmlString = file_get_contents($this->url);
        $urlParse = parse_url($this->url);
        $htmlDom = new DOMDocument;
        @$htmlDom->loadHTML($htmlString);
        $imageTags = $htmlDom->getElementsByTagName('img');
        $_SESSION['total'] = count($imageTags);

        foreach ($imageTags as $imageTag) {
            session_start();
            $_SESSION['progress'] = $count;
            session_write_close();
            sleep(1);
            $atrSrc = $imageTag->getAttribute('src');
            $imgSrc = (strpos($atrSrc, $urlParse['host']) == true) ? $atrSrc : $urlParse['scheme'] . '://' . $urlParse['host'] . '/' . $atrSrc;
            $nameFile = basename($imgSrc);
            (new Downloader($imgSrc, $urlParse['host'], $nameFile))->copyFile();
            $count++;
        }

        unset($_SESSION['progress']);

        return true;
    }
}