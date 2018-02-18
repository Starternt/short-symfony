<?php

namespace Short\ShortBundle\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Check
{
    public static function validateUrl($original_url)
    {
        $response_code = @get_headers($original_url);
        $check_code = preg_match('/([4-5]{1}[0-9]{2})/', $response_code[0]);
        if (!$response_code || $check_code) {
            $exists = false;
        } else {
            $exists = true;
        }
        return $exists;
    }


    public static function generateUrlS()
    {
        return substr(md5(uniqid()), 0, 6);
    }

}