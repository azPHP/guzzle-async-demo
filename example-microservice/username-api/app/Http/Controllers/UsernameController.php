<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class UsernameController extends BaseController
{

    public function generateUsername()
    {
        exec("fortune", $lines);

        $tokens = [];
        foreach($lines as $line)
        {
            $tokens = array_merge($tokens, preg_split("/\s+/", $line));
            usleep(rand(100000, 500000));
        }

        $first = array_rand($tokens, 1);
        $second = array_rand($tokens, 1);

        $username = ucfirst($tokens[$first]).ucfirst($tokens[$second]);
        $username = preg_replace('/[^a-zA-Z0-9-_]/', '_', $username);

        echo json_encode(['username' => $username]);
    }
}
