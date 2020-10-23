<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
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
        $dataFromUrl = $this->getDataFromUrl($strOutside);

        $result = '';

        if (!empty($dataFromUrl)) {

            //Send a GET request to the URL of the web page using file_get_contents.
//This will return the HTML source of the page as a string.
            $htmlString = file_get_contents('https://en.wikipedia.org/wiki/Main_Page');

//Create a new DOMDocument object.
            $htmlDom = new DOMDocument;

//Load the HTML string into our DOMDocument object.
            @$htmlDom->loadHTML($htmlString);

//Extract all img elements / tags from the HTML.
            $imageTags = $htmlDom->getElementsByTagName('img');

//Create an array to add extracted images to.
            $extractedImages = array();

//Loop through the image tags that DOMDocument found.
            foreach($imageTags as $imageTag){

                //Get the src attribute of the image.
                $imgSrc = $imageTag->getAttribute('src');

                //Get the alt text of the image.
                $altText = $imageTag->getAttribute('alt');

                //Get the title text of the image, if it exists.
                $titleText = $imageTag->getAttribute('title');

                //Add the image details to our $extractedImages array.
                $extractedImages[] = array(
                    'src' => $imgSrc,
                    'alt' => $altText,
                    'title' => $titleText
                );
            }

            var_dump($extractedImages);

        } else {
            $this->isAjax ? $this->ajaxResponse(false, 'Url not exist, check url!') : Route::errorPage404();
        }

        header('Content-type: text/html; charset=utf-8');
        header('Content-disposition: attachment; filename=Redirect301_' . date("Ymd_His") . '.txt');

        if ($this->isAjax) {
            $this->ajaxResponse(true, 'It’s ok!');
        } else {
            echo $result;
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
        $exampleUrl = 'http://' . $_SERVER['SERVER_NAME'] . '/img/parsing.jpg';
        $urlData = (isset($_POST['url-data']) && !empty($_POST['url-data'])) ? $_POST['url-data'] : $exampleUrl;

        try {
            $urlData = $this->validator->checkStr($urlData);
        } catch (NotValidInputException $e) {
            echo $e->getMessage();
        }

        return ['url' => $urlData];
    }
}