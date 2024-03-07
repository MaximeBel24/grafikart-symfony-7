<?php

namespace App\DTO;

use App\Validator\BanWord;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    #[Assert\NotBlank]
    #[BanWord()]
    #[Assert\Length(min: 3, max: 200)]
    public string $name = '';
    
    #[Assert\NotBlank]
    #[Assert\Email()]
    #[BanWord()]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 200)]
    #[BanWord()]
    public string $message = '';

    #[Assert\NotBlank]
    public string $service = '';

}