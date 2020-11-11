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

    public function downloadImages(): void
    {
        $htmlString = file_get_contents($this->url);
        $urlParse = parse_url($this->url);
        $htmlDom = new DOMDocument;
        @$htmlDom->loadHTML($htmlString);
        $imageTags = $htmlDom->getElementsByTagName('img');
        foreach ($imageTags as $imageTag) {
            $atrSrc = $imageTag->getAttribute('src');
            $imgSrc = (strpos($this->url, $urlParse['host']) === true) ? $atrSrc : $urlParse['scheme'] . '://' . $urlParse['host'] . $atrSrc;
            (new Downloader($imgSrc, '\downloads\test.jpg'))->copyFile();
        }
    }
}