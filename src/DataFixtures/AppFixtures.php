<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Questionnaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questionnaire = new Questionnaire();
        $questionnaire->setTitre('Échelle de stress de Holmes et Rahe');
        $manager->persist($questionnaire);

        $questions = [
            ['texte' => 'Décès du conjoint', 'poids' => 100],
            ['texte' => 'Divorce', 'poids' => 73],
            ['texte' => 'Séparation conjugale', 'poids' => 65],
            ['texte' => 'Emprisonnement', 'poids' => 63],
            ['texte' => 'Décès d\'un proche', 'poids' => 63],
            ['texte' => 'Blessure ou maladie personnelle', 'poids' => 53],
            ['texte' => 'Mariage', 'poids' => 50],
            ['texte' => 'Licenciement', 'poids' => 47],
            ['texte' => 'Réconciliation conjugale', 'poids' => 45],
            ['texte' => 'Retraite', 'poids' => 45],
            ['texte' => 'Changement de santé d\'un membre de la famille', 'poids' => 44],
            ['texte' => 'Grossesse', 'poids' => 40],
            ['texte' => 'Difficultés sexuelles', 'poids' => 39],
            ['texte' => 'Arrivée d\'un nouveau membre dans la famille', 'poids' => 39],
            ['texte' => 'Réajustement professionnel', 'poids' => 39],
            ['texte' => 'Changement de situation financière', 'poids' => 38],
            ['texte' => 'Décès d\'un ami proche', 'poids' => 37],
            ['texte' => 'Changement d\'orientation professionnelle', 'poids' => 36],
            ['texte' => 'Modification du nombre de disputes avec le conjoint', 'poids' => 35],
            ['texte' => 'Emprunt immobilier important', 'poids' => 31],
            ['texte' => 'Saisie d\'un emprunt ou d\'une hypothèque', 'poids' => 30],
            ['texte' => 'Changement de responsabilités au travail', 'poids' => 29],
            ['texte' => 'Départ d\'un enfant du foyer', 'poids' => 29],
            ['texte' => 'Difficultés avec la belle-famille', 'poids' => 29],
            ['texte' => 'Réalisation personnelle remarquable', 'poids' => 28],
            ['texte' => 'Le conjoint commence ou arrête de travailler', 'poids' => 26],
            ['texte' => 'Début ou fin de scolarité', 'poids' => 26],
            ['texte' => 'Changement de conditions de vie', 'poids' => 25],
            ['texte' => 'Changement d\'habitudes personnelles', 'poids' => 24],
            ['texte' => 'Difficultés avec son patron', 'poids' => 23],
            ['texte' => 'Changement d\'horaires ou de conditions de travail', 'poids' => 20],
            ['texte' => 'Changement de domicile', 'poids' => 20],
            ['texte' => 'Changement d\'établissement scolaire', 'poids' => 20],
            ['texte' => 'Changement de loisirs', 'poids' => 19],
            ['texte' => 'Changement d\'activités religieuses', 'poids' => 19],
            ['texte' => 'Changement d\'activités sociales', 'poids' => 18],
            ['texte' => 'Emprunt modeste', 'poids' => 17],
            ['texte' => 'Changement d\'habitudes de sommeil', 'poids' => 16],
            ['texte' => 'Changement du nombre de réunions de famille', 'poids' => 15],
            ['texte' => 'Changement d\'habitudes alimentaires', 'poids' => 15],
            ['texte' => 'Vacances', 'poids' => 13],
            ['texte' => 'Noël ou fêtes de fin d\'année', 'poids' => 12],
            ['texte' => 'Infractions mineures à la loi', 'poids' => 11],
        ];

        foreach ($questions as $q) {
            $question = new Question();
            $question->setTexte($q['texte']);
            $question->setPoids($q['poids']);
            $question->setQuestionnaire($questionnaire);
            $manager->persist($question);
        }

        $manager->flush();
    }
}
