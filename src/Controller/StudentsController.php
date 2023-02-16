<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentsController extends AbstractController
{
    #[Route('/students', name: 'app_students')]
    public function index(): Response
    {
        return $this->render('students/index.html.twig', [
            'students' => [['id' => 2, 'name' => 'Jimmy', 'lastname' => 'Week', 'sex' => 'man']],
        ]);
    }

    #[Route('/add-student', name: 'app_addStudent')]
    public function FunctionName(Type $var = null)
    {
        # code...
    }
}
