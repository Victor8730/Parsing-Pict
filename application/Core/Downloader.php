<?php
declare(strict_types=1);

namespace Core;

use Exceptions\NotExistFileFromUrlException;

class Downloader
{
    private string $url;

    private string $path;

    private string $fileName;

    private object $validator;

    public function __construct($url, $path, $fileName)
    {
        $this->url = $url;
        $this->path = $path;
        $this->fileName = $fileName;
        $this->validator = new Validator();
    }

    public function copyFile(): bool
    {
        try {
            $this->validator->checkFileExistFromUrl($this->url);
        } catch (NotExistFileFromUrlException $e) {
            return false;
        }

        $file = file_get_contents($this->url);
        $adrFile = BASE::PATH_DOWNLOAD . '/' . $this->path;
        (!is_dir($adrFile)) ? mkdir($adrFile) : null;
        file_put_contents($adrFile . '/' . $this->fileName, $file);

        return true;
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
