<?php

namespace App\DataFixtures;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

trait FixturesInjection
{
    public $iteration = 5;
    public $taskPerUser = 3;
    public $encoder;
    public static $userPassword = "admin";

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
}