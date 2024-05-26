<?php

namespace App\Controller;

use App\Model\Team;
use App\Model\Team\Listing;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FootballTeamController extends FrontendController
{
    #[Route('/football/teams', name: 'football_teams')]
    public function indexAction(Request $request,): Response {
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset', 0);

        $list = new Team\Listing();
        $list->load();

        $teams = [];
        if (is_array($list->getItems($offset, $limit))) {
            foreach ($list->getItems($offset, $limit) as $team) {
                $teams[] = Team::getById($team);
            }
        }

        return $this->render('football/teams/index.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/football/teams/{id}', name: 'team_show')]
    public function showAction(
        Request $request
    ): Response {
        $id = $request->attributes->getInt('id');

        $team = Team::getByIdWithPlayers($id);

        return $this->render('football/teams/show.html.twig', [
            'team' => $team,
        ]);
    }
}
