<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $user;

    /**
     * TwigEventSubscriber constructor.
     * @param Environment $twig
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(Environment $twig, TokenStorageInterface $tokenStorage)
    {
        $this->twig = $twig;
        $this->user = $tokenStorage->getToken();
    }

    /**
     * @param ControllerEvent $event
     * @return void
     */
    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->twig->addGlobal('user', $this->user);
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}