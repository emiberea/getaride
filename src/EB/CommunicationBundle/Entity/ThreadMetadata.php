<?php

namespace EB\CommunicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Table(name="fos_thread_metadata")
 * @ORM\Entity
 */
class ThreadMetadata extends BaseThreadMetadata
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
     * @ORM\ManyToOne(targetEntity="EB\CommunicationBundle\Entity\Thread", inversedBy="metadata")
     */
    protected $thread;

    /**
     * @var ParticipantInterface
     *
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User")
     */
    protected $participant;
}
