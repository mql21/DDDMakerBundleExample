<?php

namespace App\Controller;

use Mql21\DDDMakerBundle\MakeCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private MakeCommand $makerBundle;

    public function __construct(MakeCommand $makerBundle)
    {
        $this->makerBundle = $makerBundle;
    }

    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {

        return $this->render('test/index.html.twig', [
            'test' => $this->makerBundle->test(),
            'controller_name' => 'TestController',
        ]);
    }
}
