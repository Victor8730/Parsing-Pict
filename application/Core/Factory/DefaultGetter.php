<?php

declare(strict_types=1);

namespace Core\Factory;

use Core\Face\SiteImageLoader;

class DefaultGetter extends SiteImageGetter
{
    /**
     * Link to the site from which you want to download the images
     *
     * @var string
     */
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getSiteImage(): SiteImageLoader
    {
        return new DefaultLoader($this->url);
    }
}