<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Route;
use Exceptions\NotExistFileFromUrlException;
use Exceptions\NotValidDataFromUrlException;
use Exceptions\NotValidInputException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class ControllerMain extends Controller
{
    /**
     * Show page with template main
     */
    public function actionIndex()
    {
        try {
            echo $this->view->render('main/' . $this->getNameView(), ['ver'=> $this::VERSION]);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }


    public function actionDownload(): void
    {
        $strOutside = $this->validate();
        $dataFromUrl = $this->getDataFromUrl($strOutside);

        $result = 'RewriteEngine On' . "\n";

        if (!empty($dataFromUrl)) {
            $oldDomains = $dataFromUrl->url->loc[0];
            foreach ($dataFromUrl->url as $item) {
                $oldUrl = str_replace($oldDomains, '', $item->loc);
                $newUrl = str_replace($oldDomains, $strOutside['domains'], $item->loc);
                $result .= 'Redirect 301 /' . $oldUrl . ' ' . $newUrl . "\n";
            }
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
     * generate example xml
     * @return string
     */
    public function exampleXml(): string
    {
        $dom = new \DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;
        $root = $dom->createElement('urlset');
        $movie_node = $dom->createElement('url');
        $child_node_title = $dom->createElement('loc', 'https://site.com/testurlforexample');
        $movie_node->appendChild($child_node_title);
        $child_node_year = $dom->createElement('lastmod', '2018-09-25T18:03:49+01:00');
        $movie_node->appendChild($child_node_year);
        $childNodeLng = $dom->createElement('priority', '1');
        $movie_node->appendChild($childNodeLng);
        $root->appendChild($movie_node);
        $dom->appendChild($root);

        return $dom->saveXML();
    }

    /**
     * Get jpg file, if exist
     * @param array $dataOutside
     * @return false|string|null
     */
    private function getDataFromUrl(array $dataOutside)
    {
        try {
            $this->validator->checkFileExistFromUrl($dataOutside['url']);
            $jpgFile = file_get_contents($dataOutside['url']);
        } catch (NotExistFileFromUrlException $e) {
            return null;
        }

        return $jpgFile;
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