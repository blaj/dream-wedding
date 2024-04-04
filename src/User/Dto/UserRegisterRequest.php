<?php

namespace App\User\Dto;

use App\User\Validator\EmailIsFree;
use App\User\Validator\UsernameIsFree;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

#[UsernameIsFree(isCreate: true)]
#[EmailIsFree(isCreate: true)]
class UserRegisterRequest implements UsernameInterface, EmailInterface {

  #[NotBlank]
  #[Length(min: 3, max: 50)]
  private string $username;

  #[NotBlank]
  #[Length(min: 3, max: 200)]
  #[Email]
  private string $email;

  #[NotBlank]
  #[Length(min: 8, max: 50)]
  #[NotCompromisedPassword]
  private string $password;

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }
}