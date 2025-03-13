<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class Home extends Controller
{

    public function index()
    {
        return $this->view("Home/index.php");
    }

    public function show($id)
    {
        echo "id: $id";
    }
}
