<?php

declare(strict_types=1);

namespace Core\Factory;

use core\Face\SiteImageLoader;

abstract class SiteImageGetter
{
    abstract public function factoryMethod(): SiteImageLoader;

    public function get(): void
    {
        $site = $this->factoryMethod();
        $site->downloadImages();
    }
}