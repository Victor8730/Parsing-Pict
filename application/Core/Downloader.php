<?php
declare(strict_types=1);

namespace Core;

class Downloader
{
    public string $url;
    public string $path;

    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }

    public function copyFile(): bool
    {
        $file = file_get_contents($this->url);

        if (!file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $this->path, $file)) {
            return false;
        } else {
            return true;
        }
    }

    public function copyFileCurl(): bool
    {
        $cInit = curl_init($this->url);
        $fileDownload = fopen($_SERVER['DOCUMENT_ROOT'] . '/' . $this->path, 'wb');
        curl_setopt($cInit, CURLOPT_FILE, $fileDownload);
        curl_setopt($cInit, CURLOPT_HEADER, 0);
        curl_exec($cInit);
        curl_close($cInit);
        fclose($fileDownload);

        return true;
    }
}