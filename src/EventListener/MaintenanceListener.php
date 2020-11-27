<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

//https://codereviewvideos.com/course/member-questions/video/how-can-i-create-a-maintenance-page-in-symfony

class MaintenanceListener
{
    private $params;
    private $twig;

    public function __construct(ParameterBagInterface $parameter_bag, \Twig\Environment $twig)
    {
        $this->params = $parameter_bag;
        $this->twig = $twig;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // This will get the value of our maintenance parameter
        $maintenance = $this->params->get('maintenance') ? $this->params->get('maintenance') : false;
        if ($maintenance)
        {
            $page = $this->twig->render('unavailableSuccess.html.twig');

        $event->setResponse(
          new Response(
            $page,
            Response::HTTP_SERVICE_UNAVAILABLE
          )
        );
        $event->stopPropagation();
        }
    }
}
