<?php

namespace Short\ShortBundle\Controller;

use Short\ShortBundle\Entity\Short;
use Short\ShortBundle\Form\ShortType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


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

        if ($form->isSubmitted() && $form->isValid()) {
            $check = $this->container->get('app.check');

            $original_url = $form->get('original_url')->getData();
            $desired_url = $form->get('desired_url')->getData();

            if (!$check->validateUrl($original_url)) {
                $valid_error_origin = true;
                return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
                    'valid_error_origin' => $valid_error_origin, 'url' => false));
            }

            if ($desired_url == null || empty($desired_url)) {
                $url = $check->saveUrl($original_url, $em, $short);

                return $this->render('ShortShortBundle:main:index.html.twig', array('form' => $form->createView(),
                    'url' => $url, 'server' => $_SERVER['SERVER_NAME']));
            } else {
                $isUrlExists = $em->getRepository('ShortShortBundle:Short')->findOneBy(array('short_url' => $desired_url));
                if (!$isUrlExists) {
                    $desired_url = $check->saveUrl($original_url, $em, $short, $desired_url);

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
    public function redirectAction($url)
    {
        $url = trim($url);
        $url = (string)$url;
        $url = htmlspecialchars($url);
        $em = $this->getDoctrine()->getManager();
        $requestedUrl = $em->getRepository('ShortShortBundle:Short')->findOneBy(array('short_url' => $url));
        if (!$requestedUrl) {
            throw $this->createNotFoundException(
                'URL not found:  ' . $url
            );
        } else {
            $uses = $requestedUrl->getUses() + 1;
            $requestedUrl->setUses($uses);
            $em->persist($requestedUrl);
            $em->flush();

            return $this->redirect($requestedUrl->getOriginalUrl());
        }
    }
}




