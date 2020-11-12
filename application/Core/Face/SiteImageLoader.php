<?php

declare(strict_types=1);

namespace Core\Face;

interface SiteImageLoader
{
    public function downloadImages(): bool;
}