<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{

    /**
     *
     * @return array
     */
    public function indexAction()
    {

        $pageTable = $this->getDependencyContainer()
            ->createCmsPageTable()
        ;

        $redirectTable = $this->getDependencyContainer()
            ->createCmsRedirectTable()
        ;

        return [
            'pages' => $pageTable->render(),
            'redirects' => $redirectTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function pageTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsPageTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return JsonResponse
     */
    public function redirectTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsRedirectTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

}
