<?php

namespace EB\RideBundle\Command;

use EB\RideBundle\Entity\RideRequestStatus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use EB\CommunicationBundle\Event\NotificationEvent;
use EB\CommunicationBundle\Event\NotificationEvents;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideStatus;

class RideCommand extends ContainerAwareCommand
{
    /** @var InputInterface $input */
    private $input;

    /** @var OutputInterface $output */
    private $output;

    protected function configure()
    {
        $this->setName('ride:ride-command')
            ->setDescription('Check all rides and verify their status, and notify users.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var RideStatus $rideStatusClosed */
        $rideStatusClosed = $em->getRepository('EBRideBundle:RideStatus')->find(RideStatus::CLOSED);

        /** @var ArrayCollection|Ride[] $rides */
        $rides = $em->getRepository('EBRideBundle:Ride')->getByAvailableStatusAndExpireStartDate();
        foreach ($rides as $ride) {
            $ride->setRideStatus($rideStatusClosed);
            $em->persist($ride);

            // dispatching the RIDE_CLOSED event, which triggers the listener to send a notification to the user that created the ride
            // and the users that have the rideRequest accepted and invite them to give a rating to each other
            $dispatcher = $this->getContainer()->get('event_dispatcher');
            $dispatcher->dispatch(NotificationEvents::RIDE_CLOSED, new NotificationEvent(array(
                'ride' => $ride,
            )));
        }

        $em->flush();
    }
}
