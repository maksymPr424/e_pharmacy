<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->query->get('_locale', $request->getSession()->get('_locale', $this->defaultLocale));

        if (in_array($locale, ['en', 'pl'])) {
            $request->setLocale($locale);
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($this->defaultLocale);
            $request->getSession()->set('_locale', $this->defaultLocale);
        }
    }
}