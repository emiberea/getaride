<?php

namespace EB\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Table(name="fos_thread")
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="EB\UserBundle\Entity\User")
     */
    protected $createdBy;

    /**
     * @var Message[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="EB\MessageBundle\Entity\Message", mappedBy="thread")
     */
    protected $messages;

    /**
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="EB\MessageBundle\Entity\ThreadMetadata", mappedBy="thread", cascade={"all"})
     */
    protected $metadata;
}
