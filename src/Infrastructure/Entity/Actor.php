<?php

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 * @ORM\Table(name="actor")
 */
class Actor
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public int $id;

    /**
     * @ORM\Column(type="string")
     */
    public string $login;

    /**
     * @ORM\Column(type="string")
     */
    public string $url;

    /**
     * @ORM\Column(type="string")
     */
    public string $avatarUrl;

    public function __construct(int $id, string $login, string $url, string $avatarUrl)
    {
        $this->id = $id;
        $this->login = $login;
        $this->url = $url;
        $this->avatarUrl = $avatarUrl;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function login(): string
    {
        return $this->login;
    }


    public function url(): string
    {
        return $this->url;
    }

    public function avatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['login'],
            $data['url'],
            $data['avatar_url']
        );
    }

}
