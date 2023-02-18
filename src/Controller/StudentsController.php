<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Form\StudentUpdateType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentsController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $orm;

    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->orm = $doctrine->getManager();
    }

    #[Route('/students', name: 'app_students')]
    public function index(): Response
    {
        $students = $this->orm->getRepository(Student::class)->findAll();
        foreach($students as $student) {
            $student->getSex() 
                ? $student->setSex('Male')
                : $student->setSex('Female');
        }
        return $this->render('students/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/add-student', name: 'app_addStudent')]
    public function createStudent(Request $request, ManagerRegistry $doctrine): Response
    { 
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();

            $this->orm->persist($student);
            $this->orm->flush();

            return $this->redirectToRoute('app_students');
        }

        return $this->render('students/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete-student/{id}', name: 'app_deleteStudent')]
    public function deleteStudent(int $id, ManagerRegistry $doctrine)
    {
        $student = $this->orm->getRepository(Student::class)->find($id);
        if($student) {
            $this->orm->remove($student);
            $this->orm->flush();
        }
        return $this->redirectToRoute('app_students');
    }

    // It's sus, but sometimes in lastnames can be character '-'.
    #[Route('/edit-student/{id}', name: 'app_updateStudent', defaults:['name' => null, 'lastname' => null, 'sex' => null])]
    public function editStudent(int $id, Request $request)
    {
        $student = $this->orm->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();

            $this->orm->persist($student);
            $this->orm->flush();

            return $this->redirectToRoute('app_students');
        }

        return $this->render('students/new.html.twig', [
            'form' => $form,
        ]);
    }
}
