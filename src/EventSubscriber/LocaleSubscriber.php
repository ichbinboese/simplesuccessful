<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Sets the Request locale based on the browser's Accept-Language header.
 * Chooses the best match among the supported locales and falls back to default.
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    /** @var string[] */
    private array $supportedLocales;

    private string $defaultLocale;

    /**
     * @param string[] $supportedLocales
     */
    public function __construct(array $supportedLocales = ['en', 'de', 'fr', 'es', 'zh'], string $defaultLocale = 'en')
    {
        $this->supportedLocales = $supportedLocales;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents(): array
    {
        // Set a high priority so it runs early in the request
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 100],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // If the route already decided a _locale, respect it
        $routeLocale = $request->attributes->get('_locale');
        if (is_string($routeLocale) && $routeLocale !== '') {
            $request->setLocale($routeLocale);
            return;
        }

        // Use the browser's preferred language among supported locales
        $preferred = $request->getPreferredLanguage($this->supportedLocales) ?: $this->defaultLocale;

        $request->attributes->set('_locale', $preferred);
        $request->setLocale($preferred);
    }
}
