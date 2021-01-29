<?php

namespace App\Controller\Admin\Config;

use App\Entity\File;
use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use App\Service\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/settings", name="admin_config_")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(Request $request, ConfigRepository $configRepository): Response
    {
        $config = $configRepository->find(1);
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($form->get('logo')->getData() !== null) {
                $fileManager = new FileManager($this->getParameter('uploads_directory'));

                if ($config->getLogo() !== null) {
                    $fileManager->delete($config->getLogo()->getPath());
                    $entityManager->remove($config->getLogo());
                }

                $file = new File();
                $file->setPath($fileManager->upload($form->get('logo')->getData()));
                $config->setLogo($file);
                $entityManager->persist($file);
            }

            $entityManager->persist($config);
            $entityManager->flush();

            return $this->redirectToRoute('admin_config_index');
        }

        return $this->render('admin/config/index.html.twig', [
            'config' => $config,
            'form' => $form->createView(),
        ]);
    }
}
