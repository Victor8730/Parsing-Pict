<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Factory\DefaultGetter;
use Core\Factory\SiteImageGetter;
use Core\Route;
use Exceptions\NotExistFileFromUrlException;
use Exceptions\NotValidInputException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class ControllerUrl extends Controller
{
    /**
     * Show page with template url
     */
    public function actionIndex()
    {
        try {
            echo $this->view->render('url/' . $this->getNameView());
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }

    private function Downloader(SiteImageGetter $getter)
    {
        return $getter->get();
    }

    public function actionDownload(): void
    {
        $strOutside = $this->validate();
        $dataFromUrl = $this->getDataFromUrl($strOutside);

        if (!empty($dataFromUrl)) {
            if($this->isAjax){
                $this->ajaxResponse(true, 'Url OK!');
            }else {
                $this->Downloader(new DefaultGetter($dataFromUrl));
            }
        } else {
            $this->isAjax ? $this->ajaxResponse(false, 'Url not exist, check url!') : Route::errorPage404();
        }
    }

    /**
     * Get all html from url, if exist data
     * @param array $dataOutside
     * @return false|string|null
     */
    private function getDataFromUrl(array $dataOutside): ?string
    {
        try {
            $this->validator->checkFileExistFromUrl($dataOutside['url']);
            $data = file_get_contents($dataOutside['url']);
        } catch (NotExistFileFromUrlException $e) {
            $this->ajaxResponse(false, 'Url bad!');
            return null;
        }

        return $data;
    }

    /**
     * Check data validity
     * @return array
     */
    private function validate(): array
    {
        $urlData = $_POST['url-data'] ?? '';

        try {
            $urlData = $this->validator->checkStr($urlData);
        } catch (NotValidInputException $e) {
            echo $e->getMessage();
        }

        return ['url' => $urlData];
    }
}