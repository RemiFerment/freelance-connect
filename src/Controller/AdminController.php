<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Interfaces\MissionManagerInterface;
use App\Form\AdminMissionFilterType;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/missions', name: 'app_admin_missions', methods: ['GET'])]
    public function missions(Request $request, MissionRepository $missionRepository): Response
    {
        $form = $this->createForm(AdminMissionFilterType::class, null, [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);
        $filters = $form->getData() ?? [];
        $missions = $missionRepository->findAllFiltered($filters);

        return $this->render('admin/missions.html.twig', [
            'missions' => $missions,
            'form' => $form,
        ]);
    }

    #[Route('/mission/{id}/delete', name: 'app_admin_mission_delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, MissionManagerInterface $missionManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mission->getId(), $request->getPayload()->getString('_token'))) {
            $missionManager->delete($mission);
            $this->addFlash('success', 'La mission a bien été supprimée.');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue, la mission n\'a pas pu être supprimée.');
        }

        return $this->redirectToRoute('app_admin_missions', [], Response::HTTP_SEE_OTHER);
    }
}