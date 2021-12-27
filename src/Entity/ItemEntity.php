<?php

namespace App\Entity;

use App\Repository\ItemEntityRepository;
use Doctrine\DBAL\Types\BigIntType;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemEntityRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="item")
 */
class ItemEntity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="guid", length=100, unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @var string A "Y-m-d H-i-s" formatted value
     */
    private $tgl_input;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    private $tgl_update;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank
     */
    private $user_input;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank
     */
    private $user_update;

    public function __construct() { }

    public function ItemEntityV1(String $name, String $value, String $description, DateTimeType $tgl_input, DateTimeType $tgl_update, String $user_input, String $user_update)
    {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->tgl_input = $tgl_input;
        $this->tgl_update = $tgl_update;
        $this->user_input = $user_input;
        $this->user_update = $user_update;
    }

    public function ItemEntityV2(String $name, String $value, String $description, Int $id = NULL, String $uuid = NULL)
    {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        //$this->tgl_input = new \DateTime('now');
        //$this->tgl_update = new \DateTime('now');
        $this->updatedTimestamps();
        $this->id = $id;
        $this->uuid = $uuid ?? Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTglInput(): ?string
    {
        return $this->tgl_input;
    }

    public function setTglInput(\DateTimeInterface $tgl_input): self
    {
        $this->tgl_input = $tgl_input;

        return $this;
    }

    public function getTglUpdate(): ?string
    {
        return $this->tgl_update;
    }

    public function setTglUpdate(\DateTimeInterface $tgl_update): self
    {
        $this->tgl_update = $tgl_update;

        return $this;
    }

    public function getUserInput(): ?string
    {
        return $this->user_input;
    }

    public function setUserInput(?string $user_input): self
    {
        $this->user_input = $user_input;

        return $this;
    }

    public function getUserUpdate(): ?string
    {
        return $this->user_update;
    }

    public function setUserUpdate(?string $user_update): self
    {
        $this->user_update = $user_update;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setTglUpdate(new \DateTime('now'));
        if ($this->getTglInput() === null) $this->setTglInput(new \DateTime('now'));
    }

    public function fromArray(array $userInput)
    {
        foreach ($userInput as $key => $value) {
            $this->$key = $value;
        }
    }

}
