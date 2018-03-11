<?php

namespace Short\ShortBundle\Controller;

use Short\ShortBundle\Entity\Short;
use Short\ShortBundle\Form\ShortType;
use Short\ShortBundle\ShortShortBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // Для аннотаций
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;
use Short\ShortBundle\Entity\Check;


/**
 * Post controller.
 * @Route("/")
 */
class ShortController extends Controller
{
    /**
     * @Route("/", name="_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $short = new Short;
        $form = $this->createForm(ShortType::class);
        $form->handleRequest($request);

        $valid_error_origin = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $original_url = $form->get('original_url')->getData();
            $desired_url = $form->get('desired_url')->getData();

            if (!Check::validateUrl($original_url)) {
                $valid_error_origin = true;
                return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
                    'valid_error_origin' => $valid_error_origin, 'url' => false));
            }

            if ($desired_url == null || empty($desired_url)) {
                $url = Check::saveUrl($original_url, $em, $short);

                return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
                    'url' => $url, 'server' => $_SERVER['SERVER_NAME']));
            } else {
                $isUrlExists = $em->getRepository('ShortShortBundle:Short')->findOneBy(array('short_url' => $desired_url));
                if (!$isUrlExists) {
                    $short->setOriginalUrl($original_url);
                    $short->setShortUrl($desired_url);
                    $short->setUses(0);

                    $em->persist($short);
                    $em->flush();

                    return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
                        'url' => $desired_url, 'server' => $_SERVER['SERVER_NAME']));
                } else {

                    return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
                        'valid_error_desired' => true, 'url' => false, 'server' => $_SERVER['SERVER_NAME']));
                }
            }

        }

        return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
            'url' => false, 'server' => $_SERVER['SERVER_NAME']));
    }

    /**
     * @Route("/{url}", name="_redirect")
     * $Method("POST")
     */
    public function redirectAction($url, Request $request)
    {
        $url = trim($url);
        $url = (string)$url;
        $url = htmlspecialchars($url);
        $em = $this->getDoctrine()->getManager();
        $requestedUrl = $em->getRepository('ShortShortBundle:Short')->findOneBy(array('short_url' => $url));
        if (!$requestedUrl) {
            throw $this->createNotFoundException(
                'No product found for URL:  ' . $url
            );
        } else {
            $uses = $requestedUrl->getUses() + 1;
            $requestedUrl->setUses($uses);
            $em->persist($requestedUrl);
            $em->flush();

            return $this->redirect($requestedUrl->getOriginalUrl());
        }


//        return $this->render('ShortShortBundle:main:test.html.twig', array('url' => $requestedUrl));
    }


}




