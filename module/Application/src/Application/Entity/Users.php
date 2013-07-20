<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 *  @ORM\Table(name="users",
 *             indexes={@ORM\Index(name="index_users_on_id_and_type", columns={"id", "type"}),
 *                      @ORM\Index(name="index_users_on_auth_source_id", columns={"auth_source_id"}),
 *                      @ORM\Index(name="index_users_on_type", columns={"type"})},
 *             options={"engine"="InnoDB", "collate"="utf8_general_ci"})
 */
class Users {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $login;

    /** @ORM\Column(type="string", length=40, options={"default" = ""}) */
    protected $hashed_password;

    /** @ORM\Column(type="string", length=30, options={"default" = ""}) */
    protected $firstname;

    /** @ORM\Column(type="string", options={"default" = ""}) */
    protected $lastname;

    /** @ORM\Column(type="string", length=60, options={"default" = ""}) */
    protected $mail;

    /** @ORM\Column(type="boolean", options={"default" = 0}) */
    protected $admin;

    /** @ORM\Column(type="integer", options={"default" = 1}) */
    protected $status;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $last_login_on;

    /** @ORM\Column(type="string", length=5, nullable=true, options={"default" = ""}) */
    protected $language;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $auth_source_id;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $created_on;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $updated_on;

    /** @ORM\Column(type="string", nullable=true) */
    protected $type;

    /** @ORM\Column(type="string", nullable=true) */
    protected $identity_url;

    /** @ORM\Column(type="string", options={"default" = ""}) */
    protected $mail_notification;

    /** @ORM\Column(type="string", length=64, nullable=true) */
    protected $salt;

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    // getters/setters
}