<?php

namespace App\User\Entity;

use App\Common\Entity\AuditingEntity;
use App\Localization\Enum\Localization;
use App\User\Entity\Enum\Role;
use App\User\Repository\UserRepository;
use App\Wedding\Entity\WeddingUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'users', schema: 'users')]
class User extends AuditingEntity implements PasswordAuthenticatedUserInterface {

  #[Column(name: 'username', type: Types::STRING, length: 50, nullable: false)]
  private string $username;

  #[Column(name: 'password', type: Types::STRING, length: 200, nullable: false)]
  private string $password;

  #[Column(name: 'email', type: Types::STRING, length: 200, nullable: false)]
  private string $email;

  #[Column(name: 'language', type: Types::STRING, length: 2, nullable: false, enumType: Localization::class)]
  private Localization $language = Localization::PL;

  #[Column(name: 'role', type: Types::STRING, length: 20, nullable: false, enumType: Role::class)]
  private Role $role = Role::USER;

  /**
   * @var Collection<int, WeddingUser>
   */
  #[OneToMany(targetEntity: WeddingUser::class, mappedBy: 'user', fetch: 'LAZY')]
  private Collection $weddingUsers;

  public function __construct() {
    $this->weddingUsers = new ArrayCollection();
  }

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getLanguage(): Localization {
    return $this->language;
  }

  public function setLanguage(Localization $language): self {
    $this->language = $language;

    return $this;
  }

  public function getRole(): Role {
    return $this->role;
  }

  public function setRole(Role $role): self {
    $this->role = $role;

    return $this;
  }

  /**
   * @return Collection<int, WeddingUser>
   */
  public function getWeddingUsers(): Collection {
    return $this->weddingUsers;
  }

  /**
   * @param Collection<int, WeddingUser> $weddingUsers
   */
  public function setWeddingUsers(Collection $weddingUsers): self {
    $this->weddingUsers = $weddingUsers;

    return $this;
  }

  public function addWeddingUser(WeddingUser $weddingUser): self {
    if (!$this->weddingUsers->contains($weddingUser)) {
      $this->weddingUsers->add($weddingUser);
    }

    return $this;
  }

  public function removeWeddingUser(WeddingUser $weddingUser): self {
    $this->weddingUsers->removeElement($weddingUser);

    return $this;
  }
}