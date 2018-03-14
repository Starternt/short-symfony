<?php

namespace Short\ShortBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Check
{

    public function validateUrl($original_url)
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


    public function generateUrlS()
    {
        return substr(md5(uniqid()), 0, 6);
    }

    public function saveUrl($original_url ,$em, $short, $isDesired = 0){
        do {
            if(!$isDesired){
                $url = $this->generateUrlS();
            } else {
                $url = $isDesired;
            }
            $db_url = $em->getRepository('ShortShortBundle:Short')->findOneBy(array('short_url' => $url));
            if (!$db_url) {
                $short->setOriginalUrl($original_url);
                $short->setShortUrl($url);
                $short->setUses(0);
                $em->persist($short);
                $em->flush();

            }
        } while ($db_url);
        return $url;
    }

}