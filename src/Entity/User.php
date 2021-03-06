<?php
/**
 * @Author Julien Rajerison <julienrajerison5@gmail.com>
 *
 * @Description Symfony rest api
 *
 * @Content User Entity
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="boo_user")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var
     * @ORM\Column(name="_nom",type="string",length=100,nullable=true)
     */
    private $_nom;

    /**
     * @var
     * @ORM\Column(name="_prenom",type="string",length=100,nullable=true)
     */
    private $_prenom;

    /**
     * @var
     * @ORM\Column(name="_adresse",type="string",length=100,nullable=true)
     */
    private $_adresse;

    /**
     * @var
     * @ORM\Column(name="_img_url",type="string",length=200,nullable=true)
     */
    private $_imgUrl;

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->_nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->_nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->_prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->_prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->_adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->_adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getImgUrl()
    {
        return $this->_imgUrl;
    }

    /**
     * @param mixed $imgUrl
     */
    public function setImgUrl($imgUrl): void
    {
        $this->_imgUrl = $imgUrl;
    }
}
