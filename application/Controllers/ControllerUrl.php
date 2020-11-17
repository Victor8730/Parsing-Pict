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
            if ($this->isAjax && $strOutside['check'] === true) {
                $this->ajaxResponse(true, ['Url OK!']);
            } else {
                ($this->download(new DefaultGetter($strOutside['url'])) === true) ?
                    $this->ajaxResponse(true, ['Download success!']) :
                    $this->ajaxResponse(false, ['Download failed!']);
            }
        }
    }

    public function actionTotal(): int
    {
        return $_SESSION['total'] ?? 0;
    }

    public function actionListen(): void
    {
        session_start();

        //echo $_SESSION['progress'] ?? '';

        $this->ajaxResponse(false, [$_SESSION['total'], $_SESSION['progress']]);


        if (!empty($_SESSION['progress']) && $_SESSION['progress'] >= $_SESSION['total']) {
            unset($_SESSION['progress']);
        }
    }

    private function download(SiteImageGetter $getter)
    {
        return $getter->get();
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
                $this->ajaxResponse(false, ['Url Bad!']);
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
        $check = $_POST['check'] == 0 ? false : true;

        try {
            $urlData = $this->validator->checkStr($urlData);
        } catch (NotValidInputException $e) {
            echo $e->getMessage();
        }

        return ['url' => $urlData, 'check' => $check];
    }
}