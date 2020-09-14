<?php

namespace App\EventSubscriber;

use App\Lib\Tag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AppUniverseSubscriber implements EventSubscriberInterface
{
    private $params;
    private $container;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function onKernelController(ControllerEvent $event)
    {
        if ($this->params->get('universe')) {
            // is there a tag in the hostname?
            $hostname = $event->getRequest()->getHost();
            if (!preg_match($this->params->get('host_exclude_regex'), $hostname) && $pos = strpos($hostname, '.')) {
                $tag = Tag::normalize(substr($hostname, 0, $pos));

                // add a permanent tag custom parameter
                $event->getRequest()->attributes->set('app_permanent_tag', $tag);

                // add a custom stylesheet
                $event->getRequest()->attributes->set('custom_stylesheet', $tag);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
