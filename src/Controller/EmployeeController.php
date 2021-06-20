<?php


namespace App\Controller;


use App\Entity\Employee;
use App\Form\EmployeeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EmployeeController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/employee", name="employee_list");
     */
    public function index()
    {
        $employeeList = $this->entityManager->getRepository(Employee::class)->findAll();
        if($employeeList){
            return $this->render('employee/index.html.twig', [
                'employees' => $employeeList,
            ]);
        }
        $this->addFlash('error', 'Employee list is empty');
        return $this->redirectToRoute('employee_list');
    }

    /**
     * @Route("/employee/{id}", name="employee_show");
     */
    public function show(Request $request, $id)
    {
        $employee = $this->entityManager->getRepository(Employee::class)->find($id);
        if($employee instanceof Employee){

            $editForm = $this->createForm(EmployeeFormType::class, $employee, [
                'name' => $employee->getName(),
                'age' => $employee->getAge(),
                'dateOfJoining' => $employee->getDateOfJoining(),
            ]);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $requestData = $request->request->all();
                $data = $requestData['employee_form'];
                $employee->setName($data['name']);
                $employee->setAge($data['age']);
                $employee->setDateOfJoining(new \DateTime($data['dateOfJoining']));
                $this->entityManager->persist($employee);
                $this->entityManager->flush();
                $this->entityManager->clear();
                $this->addFlash('success', 'Employee details updated successfully');
                return $this->redirectToRoute('employee_list');

            }

            return $this->render('employee/show.html.twig', [
                'form' => $editForm->createView(),
                'employee' => $employee,
            ]);
        }
        $this->addFlash('error', 'Employee not exist');
        return $this->redirectToRoute('employee_list');
    }
}