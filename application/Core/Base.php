<?php

declare(strict_types=1);

namespace Core;

class Base implements face\Base
{
    /**
     * Program version
     */
    public const VERSION = '0.1.1';

    /**
     * Main folder where both frontend and backend are located
     */
    protected const PATH_ROOT = __DIR__ . '/../..';

    /**
     * Application folder name
     */
    protected const PATH_APPLICATION = 'application';

    /**
     * Controller folder name
     */
    protected const PATH_CONTROLLERS = 'Controllers';

    /**
     * Model folder name
     */
    protected const PATH_MODEL = 'Models';

    /**
     * Cache folder name
     */
    protected const PATH_CACHE = 'Cache';

    /**
     * Views folder name
     */
    protected const PATH_VIEWS = 'Views';

    /**
     * Tests folder name
     */
    protected const PATH_TESTS = 'Tests';

    /**
     * @var Validator|object
     */
    protected object $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

}