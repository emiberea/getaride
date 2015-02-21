<?php

namespace EB\RideBundle\Command;

use EB\RideBundle\Entity\RideRequestStatus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use EB\CommunicationBundle\Entity\Notification;
use EB\RideBundle\Entity\Ride;
use EB\RideBundle\Entity\RideRequest;
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

            // URLs
            $devDomain = $this->getContainer()->getParameter('domain.dev');
            $publicRideUrl = $devDomain . $this->getContainer()->get('router')->generate('ride_show_public', array('id' => $ride->getId()));

            /** @var ArrayCollection|RideRequest[] $rideRequests */
            $rideRequests = $ride->getRideRequests();
            foreach ($rideRequests as $rideRequest) {
                if ($rideRequest->getStatus()->getId() == RideRequestStatus::ACCEPTED) {
                    // creating notifications for the driver and the passenger and save them to the DB
                    // notify the them that the ride is closer (over) and that they could give a rating score to each other
                    $driverNotification = new Notification();
                    $driverNotification->setIsRead(false);
                    $driverNotification->setDate(new \DateTime());
                    $driverNotification->setRedirectUrl1($publicRideUrl);
                    $driverNotification->setRedirectUrl2(
                        $devDomain . $this->getContainer()->get('router')->generate('ride_request_rating', array('id' => $rideRequest->getId()))
                    );
                    $driverNotification->setType(Notification::TYPE_RIDE_CLOSED);
                    $driverNotification->setInitiatorUser($rideRequest->getUser());
                    $driverNotification->setReceiverUser($ride->getUser());
                    $driverNotification->setRideRequest($rideRequest);

                    $passengerNotification = new Notification();
                    $passengerNotification->setIsRead(false);
                    $passengerNotification->setDate(new \DateTime());
                    $passengerNotification->setRedirectUrl1($publicRideUrl);
                    $passengerNotification->setRedirectUrl2(
                        $devDomain . $this->getContainer()->get('router')->generate('ride_request_rating', array('id' => $rideRequest->getId()))
                    );
                    $passengerNotification->setType(Notification::TYPE_RIDE_CLOSED);
                    $passengerNotification->setInitiatorUser($ride->getUser());
                    $passengerNotification->setReceiverUser($rideRequest->getUser());
                    $passengerNotification->setRideRequest($rideRequest);

                    $em->persist($driverNotification);
                    $em->persist($passengerNotification);
                }
            }
        }

        $em->flush();
    }
}
