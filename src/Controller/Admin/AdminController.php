<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2018
 * Time: 10:59
 */

namespace App\Controller\Admin;


use App\Entity\Box;
use App\Form\BoxType;
use App\Provider\ProductsProvider;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Yaml\Yaml;


class AdminController extends AbstractController
{

    public $workflows;

    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
    }

    /**
     * @Route("/admin", name="administration_main")
     */
    public function admin()
    {
        
        return $this->render('admin/main.html.twig');
    }


    /**
     * @Route("/admin/current_box", name="administration_current_box")
     */
    public function currentBox()
    {
        return $this->render('admin/current_box.html.twig');
    }


    /**
     * @Route("/admin/current_box/choose_products", name="administration_current_box_choosing", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function chooseProductForBox(ObjectManager $em)
    {
        $yamlConponent = new Yaml();
        $file = file_get_contents(__DIR__.'\..\..\Provider\kawaii_products.yaml');

        # Getting our products
        $products = new ProductsProvider();
        $products = $products->productArrayFromYaml($yamlConponent, $file);
        $products = $products['products'];

        $activeBox = $em->getRepository(Box::class)->findActiveBox();

        # If there'snt an active box
        if (empty($activeBox))
        {
            return $this->redirectToRoute('admin_new_box');
        }

        $myBox = $activeBox[0];

        if (!empty($_POST)){

            foreach ($_POST as $selectedProducts => $value)
            {
                if ($value == 'delete')
                {
                    $productToRemove = $products[$selectedProducts];
                    $myBox->removeProduct($productToRemove);
                }
                elseif ($value == 'validate_order')
                {
                    # change status
                    $myBox->changeStatus('Ordered_from_catalogue');

                    $workflows = $this->workflows->get($myBox);
                    $workflows->apply($myBox, 'order_passed');
                    # redirect to next page

                    return $this->render('admin/current_box_order_has_arrived.html.twig');
                }
                elseif ($value !== 'delete')
                {
                    $productToAdd = $products[$selectedProducts];
                    # add to Box;
                    $myBox->addProduct($productToAdd);
                }
            }
            $em->flush();
        }

        return $this->render('admin/current_box_choose_products.html.twig', [
            'products'  => $products,
            'box'       => $myBox
        ]);
    }


    /**
     * @Route("/admin/create_new_box", name="admin_new_box", methods={"GET", "POST"})
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createNewBox(Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(BoxType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('administration_current_box_choosing');
    }
        return $this->render('admin/create_new_box.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/current_box/delete_product", name="administration_current_box_delete_product", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeProduct(ObjectManager $em)
    {
        return $this->render('test', [
        ]);
    }

    /**
     * @Route("/admin/current_box/product_manager", name="administration_current_box_product_manager")
     */
    public function orderHasArrived(ObjectManager $objectManager)
    {
       $box =  $objectManager->getRepository(Box::class)->findActiveBox();
       $box = $box[0];

       $workflows = $this->workflows->get($box);


       if ( isset( $_POST['arrived']))
       {
            $workflows->apply($box, 'order_received');
            $objectManager->flush();
       }
       elseif (isset( $_POST['validated']) )
       {
           $workflows->apply($box, 'order_approved');
           $objectManager->flush();
       }

        return $this->render('admin/current_box_order_has_arrived.html.twig');
    }

}