<?php

declare(strict_types=1);

namespace Core;

class Controller extends Base
{
    /**
     * Object view
     * @var \Twig\Environment|object
     */
    protected object $view;

    /**
     * Working with XMLHttpRequest or not
     * @var bool
     */
    protected bool $isAjax;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this::PATH_ROOT . '/' . $this::PATH_APPLICATION . '/' . $this::PATH_VIEWS);
        $this->view = new \Twig\Environment($loader, [
            'cache' => $this::PATH_ROOT . '/' .$this::PATH_APPLICATION . '/' . $this::PATH_CACHE,
            'auto_reload' => true
        ]);
        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        parent::__construct();
    }

    /**
     * Return name class
     * @return string
     */
    public function __toString(): string
    {
        return get_class($this);
    }

    /**
     * Return the response as a json string
     * @param bool $success
     * @param array $data
     */
    protected function ajaxResponse(bool $success = true, array $data = []): void
    {
        $response = [
            'success' => ($success===true) ? 'success' : 'danger',
            'data' => $data,
        ];

        echo(json_encode($response));
    }

    /**
     * Get the name of the view and return a low-case string
     * @return string
     */
    protected function getNameView(): string
    {
        $nameTemplate = explode('\Controller', $this->__toString());
        return mb_strtolower($nameTemplate[1]) . '.twig';
    }

}