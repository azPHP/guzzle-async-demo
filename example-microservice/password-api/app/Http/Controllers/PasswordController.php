<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class PasswordController extends BaseController
{

    public function generatePassword()
    {
        exec("apg -m 20 -n 1000", $lines);

        $rand = array_rand($lines, 1);

        echo json_encode(['password' => trim($lines[$rand])]);
    }
}
