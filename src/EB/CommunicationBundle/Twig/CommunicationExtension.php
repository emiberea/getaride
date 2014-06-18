<?php

namespace EB\CommunicationBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use EB\CommunicationBundle\Entity\Notification;
use EB\UserBundle\Entity\User;

class CommunicationExtension extends \Twig_Extension
{
    /** @var  ContainerInterface $container */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('count_unread_notification', array($this, 'countUnreadNotification')),
            new \Twig_SimpleFunction('print_notif_text', array($this, 'printNotificationText')),
        );
    }

    public function getName()
    {
        return 'eb_communication_extension';
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function countUnreadNotification(User $user)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $unreadNotificationNo = $em->getRepository('EBCommunicationBundle:Notification')->countUnreadByReceiverUser($user);

        return $unreadNotificationNo;
    }

    public function printNotificationText(Notification $notification)
    {
        switch ($notification->getType()) {
            case Notification::TYPE_FRIEND_REQUEST_SENT:
                $notifText = sprintf("<b>%s</b> has sent you a friend request. You can view his/her profile and confirm the friend request ",
                    $notification->getInitiatorUser()->getFullname()
                );
                break;
            case Notification::TYPE_FRIEND_REQUEST_ACCEPTED:
                $notifText = sprintf("<b>%s</b> accepted your friend request. You can view his/her profile ",
                    $notification->getInitiatorUser()->getFullname()
                );
                break;
            case Notification::TYPE_RIDE_REQUEST_SENT:
                $notifText = sprintf("<b>%s</b> wants to join your <b>%s</b> - <b>%s</b> ride on <b>%s</b>. You can view requesting users ",
                    $notification->getInitiatorUser()->getFullname(),
                    explode(',', $notification->getRideRequest()->getRide()->getStartLocation())[0],
                    explode(',', $notification->getRideRequest()->getRide()->getStopLocation())[0],
                    $notification->getRideRequest()->getRide()->getStartDate()->format('d-m-Y H:i')
                );
                break;
            case Notification::TYPE_RIDE_REQUEST_ACCEPTED:
                $notifText = sprintf("<b>%s</b> accepted you to join his <b>%s</b> - <b>%s</b> ride on <b>%s</b>. You can view the ride details ",
                    $notification->getInitiatorUser()->getFullname(),
                    explode(',', $notification->getRideRequest()->getRide()->getStartLocation())[0],
                    explode(',', $notification->getRideRequest()->getRide()->getStopLocation())[0],
                    $notification->getRideRequest()->getRide()->getStartDate()->format('d-m-Y H:i')
                );
                break;
            case Notification::TYPE_RIDE_CANCELED:
                $notifText = sprintf("<b>%s</b> canceled his <b>%s</b> - <b>%s</b> ride on <b>%s</b>. You can view the ride details ",
                    $notification->getInitiatorUser()->getFullname(),
                    explode(',', $notification->getRideRequest()->getRide()->getStartLocation())[0],
                    explode(',', $notification->getRideRequest()->getRide()->getStopLocation())[0],
                    $notification->getRideRequest()->getRide()->getStartDate()->format('d-m-Y H:i')
                );
                break;
            case Notification::TYPE_RIDE_CLOSED:
                $notifText = sprintf("The <b>%s</b> - <b>%s</b> ride from <b>%s</b> which you had travel with <b>%s</b> is over. You can give <b>%s</b> a rating for his service <a href=\"%s\">here</a>. You also can review the ride details ",
                    explode(',', $notification->getRideRequest()->getRide()->getStartLocation())[0],
                    explode(',', $notification->getRideRequest()->getRide()->getStopLocation())[0],
                    $notification->getRideRequest()->getRide()->getStartDate()->format('d-m-Y H:i'),
                    $notification->getInitiatorUser()->getFullname(),
                    $notification->getInitiatorUser()->getFullname(),
                    $notification->getRedirectUrl2()
                );
                break;
            case Notification::TYPE_RATING_AWARDED:
                $notifText = sprintf("<b>%s</b> has awarded you a rating score for your service on the <b>%s</b> - <b>%s</b> ride on <b>%s</b>. You can view your ratings ",
                    $notification->getInitiatorUser()->getFullname(),
                    explode(',', $notification->getRideRequest()->getRide()->getStartLocation())[0],
                    explode(',', $notification->getRideRequest()->getRide()->getStopLocation())[0],
                    $notification->getRideRequest()->getRide()->getStartDate()->format('d-m-Y H:i')
                );
                break;
            default:
                throw new \Exception('Could not find notification with type: ' . $notification->getType());
        }

        return $notifText;
    }
}
