<?php

declare(strict_types=1);

namespace Core\Factory;

use Core\Face\SiteImageLoader;

abstract class SiteImageGetter
{
    abstract public function factoryMethod(): SiteImageLoader;

    public function get(): bool
    {
        $site = $this->factoryMethod();

        return $site->downloadImages();
    }
}