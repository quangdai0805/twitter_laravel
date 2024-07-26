<?php
// app/Services/AuthService.php

// app/Services/AuthServiceInterface.php

namespace App\Services;

interface AuthServiceInterface
{
    public function register(array $data);
    public function login(array $credentials);
}

