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

    public function actionDownload(): void
    {
        $strOutside = $this->validate();

        if ($this->checkDataFromUrl($strOutside) === true) {
            if ($this->isAjax) {
                $this->ajaxResponse(true, 'Url OK!');
            } else {
                $this->download(new DefaultGetter($strOutside['url']));
            }
        }
    }

    private function download(SiteImageGetter $getter)
    {
        $getter->get();
    }

    /**
     * Check url, if exist data return true, else false
     * @param array $dataOutside
     * @return bool
     */
    private function checkDataFromUrl(array $dataOutside): bool
    {
        try {
            $this->validator->checkFileExistFromUrl($dataOutside['url']);
        } catch (NotExistFileFromUrlException $e) {
            if ($this->isAjax) {
                $this->ajaxResponse(false, 'Url Bad!');
            } else {
                Route::errorPage404();
            }
        }

        return true;
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