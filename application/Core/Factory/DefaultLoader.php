<?php


namespace Core\Factory;

use Core\Face\SiteImageLoader;
use DOMDocument;

class DefaultLoader implements SiteImageLoader
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function downloadImages(): void
    {
        $htmlString = $this->url;
        $htmlDom = new DOMDocument;
        @$htmlDom->loadHTML($htmlString);
        $imageTags = $htmlDom->getElementsByTagName('img');
        $extractedImages = array();
        foreach ($imageTags as $imageTag) {
            $imgSrc = $imageTag->getAttribute('src');
            $altText = $imageTag->getAttribute('alt');
            $titleText = $imageTag->getAttribute('title');
            $extractedImages[] = array(
                'src' => $imgSrc,
                'alt' => $altText,
                'title' => $titleText
            );
        }
        var_dump($extractedImages);
    }

}