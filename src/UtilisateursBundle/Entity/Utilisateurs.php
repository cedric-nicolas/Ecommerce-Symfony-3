<?php
// src/AppBundle/Entity/User.php

namespace UtilisateursBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateurs")
 */
class Utilisateurs extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->commandes = new ArrayCollection();
        $this->adresses = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="EcommerceBundle\Entity\Commandes", mappedBy="utilisateur",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="EcommerceBundle\Entity\UtilisateursAdresses", mappedBy="utilisateur",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $adresses;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Add commandes
     *
     * @param \EcommerceBundle\Entity\Commandes $commandes
     * @return Utilisateurs
     */
    public function addCommande(\EcommerceBundle\Entity\Commandes $commandes)
    {
        $this->commandes[] = $commandes;
        return $this;
    }
    /**
     * Remove commandes
     *
     * @param \EcommerceBundle\Entity\Commandes $commandes
     */
    public function removeCommande(\EcommerceBundle\Entity\Commandes $commandes)
    {
        $this->commandes->removeElement($commandes);
    }
    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }
    /**
     * Add adresses
     *
     * @param \EcommerceBundle\Entity\UtilisateursAdresses $adresses
     * @return Utilisateurs
     */
    public function addAdress(\EcommerceBundle\Entity\UtilisateursAdresses $adresses)
    {
        $this->adresses[] = $adresses;
        return $this;
    }
    /**
     * Remove adresses
     *
     * @param \EcommerceBundle\Entity\UtilisateursAdresses $adresses
     */
    public function removeAdress(\EcommerceBundle\Entity\UtilisateursAdresses $adresses)
    {
        $this->adresses->removeElement($adresses);
    }
    /**
     * Get adresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdresses()
    {
        return $this->adresses;
    }

}
