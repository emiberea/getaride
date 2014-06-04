<?php

namespace EB\CommunicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message_metadata")
 * @ORM\Entity
 */
class MessageMetadata extends BaseMessageMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var MessageInterface
     *
     * @ORM\ManyToOne(targetEntity="EB\CommunicationBundle\Entity\Message", inversedBy="metadata")
     */
    protected $message;

    /**
     * @var ParticipantInterface
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User")
     */
    protected $participant;
}
