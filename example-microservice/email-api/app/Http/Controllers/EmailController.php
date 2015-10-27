<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class EmailController extends BaseController
{

    public function generateEmail()
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
        $third = array_rand($tokens, 1);

        $username = strtolower($tokens[$first]).strtolower($tokens[$second]);
        $username = preg_replace('/[^a-zA-Z0-9-_]/', '_', $username);
        $host = preg_replace('/[^a-zA-Z0-9-_]/', '_', $tokens[$third]);

        $email = "$username@$host.io";

        echo json_encode(['email' => $email]);
    }
}
