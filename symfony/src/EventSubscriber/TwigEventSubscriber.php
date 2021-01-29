<?php

namespace App\EventSubscriber;

use App\Repository\ConfigRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $configRepository;

    public function __construct(Environment $twig, ConfigRepository $configRepository)
    {
        $this->twig = $twig;
        $this->configRepository = $configRepository;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('config', $this->configRepository->find(1));
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
