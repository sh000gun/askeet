<?php

namespace App\EventSubscriber;

use App\Lib\Tag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct( ParameterBagInterface $parameter_bag, string $defaultLocale = 'en')
    {
        $this->params = $parameter_bag;
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $hostname = $event->getRequest()->getHost();
        if (!preg_match($this->params->get('host_exclude_regex'), $hostname) && $pos = strpos($hostname, '.'))
        {
            $tag = Tag::normalize(substr($hostname, 0, $pos));
            if (is_readable($this->params->get('kernel.project_dir').'/translations/messages.'.strtolower($tag).'.yaml'))
            {
                $request->setLocale(strtolower($tag));
            }
            else
            {
                $request->setLocale($this->$defaultLocale); 
            }
        }       
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
