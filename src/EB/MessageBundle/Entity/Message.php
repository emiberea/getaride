<?php

namespace EB\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_message")
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ThreadInterface
     *
     * @ORM\ManyToOne(targetEntity="EB\MessageBundle\Entity\Thread", inversedBy="messages")
     */
    protected $thread;

    /**
     * @var ParticipantInterface
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User")
     */
    protected $sender;

    /**
     * @var MessageMetadata
     *
     * @ORM\OneToMany(targetEntity="EB\MessageBundle\Entity\MessageMetadata", mappedBy="message", cascade={"all"})
     */
    protected $metadata;
}
