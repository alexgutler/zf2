<?php
namespace AGUser\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\Math\Rand,
    Zend\Crypt\Key\Derivation\Pbkdf2;

use Zend\Stdlib\Hydrator;

/**
 * AguserUsers
 * 
 * @ORM\Table(name="aguser_users")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User 
{
    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
     */
    private $nome;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;
    
    /**
     * @var tinyint
     * 
     * @ORM\Column(name="active", type="tinyint", nullable=true)
     */
    private $active;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="activation_key", type="string", length=255, nullable=false)
     */
    private $activationKey;  
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;
    
    public function __construct(array $options = array()) 
    {
        /*
        $hydrator = new Hydrator\ClassMethods();
        $hydrator->hydrate($options, $this);
        */
        
        (new Hydrator\ClassMethods())->hydrate($options, $this);
        
        $this->createdAt = new \DateTime('Now');
        $this->updatedAt = new \DateTime('Now');
        
        $this->salt = base64_encode(Rand::getBytes(8, true));
        $this->activationKey = md5($this->email.$this->salt);
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getActive() {
        return $this->active;
    }

    public function getActivationKey() {
        return $this->activationKey;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $this->encryptPassword($password);
        return $this;
    }
    
    public function encryptPassword($password)
    {
        return base64_encode(Pbkdf2::calc('sha256', $password, $this->salt, 10000, strlen($password)*2));
    }
    
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    public function setActive(tinyint $active) {
        $this->active = $active;
        return $this;
    }

    public function setActivationKey($activationKey) {
        $this->activationKey = $activationKey;
        return $this;
    }

    /**
     * executa esse metodo antes de persistir os dados no banco
     * faz-se necessário declarar a annotation ORM\HasLifecycleCallbacks no inicio do arquivo
     * @ORM\prePersist
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime('Now');
    }

    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime('Now');
    }

}
