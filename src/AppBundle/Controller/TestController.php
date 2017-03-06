<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TestController extends FOSRestController
{

    /**
     * Get a test
     * @ApiDoc()
     * @View()
     */
    public function getTestAction()
    {
        $test = new \stdClass();
        $test->ok = true;

        return $test;
    }
}
