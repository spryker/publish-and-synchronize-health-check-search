<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacade getFacade()
 */
class RedirectAfterLoginProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const REQUEST_URI = 'request uri';
    const LOGIN_URI = '/auth/login';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::REQUEST, [$this, 'onKernelRequest']);
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->canRedirectAfterLogin($request)) {
            $requestUri = $request->getRequestUri();
            $request->getSession()->set(self::REQUEST_URI, $requestUri);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function canRedirectAfterLogin(Request $request)
    {
        if ($request->getMethod() !== Request::METHOD_GET) {
            return false;
        }

        if ($this->isAuthenticated($request)) {
            return false;
        }

        $requestUri = $request->getRequestUri();

        if ($requestUri === self::LOGIN_URI) {
            return false;
        }

        if (preg_match('/_profiler/', $requestUri)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isAuthenticated(Request $request)
    {
        $authFacade = $this->getFacade();
        $token = null;

        if ($authFacade->hasCurrentUser()) {
            $token = $authFacade->getCurrentUserToken();
        }

        if ($request->headers->get(AuthConstants::AUTH_TOKEN)) {
            $token = $request->headers->get(AuthConstants::AUTH_TOKEN);
        }

        if (!$authFacade->isAuthenticated($token)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return null
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return null;
        }
        $session = $request->getSession();
        if ($session->has(self::REQUEST_URI) && $this->isAuthenticated($request)) {
            $event->setResponse(new RedirectResponse($session->get(self::REQUEST_URI)));
            $session->remove(self::REQUEST_URI);
        }
    }

}
