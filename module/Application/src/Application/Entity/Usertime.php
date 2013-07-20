<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 *  @ORM\Table(name="usertime",
 *             indexes={@ORM\Index(name="user_id", columns={"user_id"})},
 *             options={"engine"="MyISAM", "collate"="utf8_general_ci"})
 */
class Usertime {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="integer") */
    protected $user_id;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $startTime;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $stopTime;

    /** @ORM\Column(type="boolean", nullable=true, options={"default" = 0}) */
    protected $isOld;

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getId()
    {
        return $this->id;
    }

    // getters/setters
}