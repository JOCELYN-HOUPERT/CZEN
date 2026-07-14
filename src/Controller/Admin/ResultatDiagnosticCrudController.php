<?php

namespace App\Controller\Admin;

use App\Entity\ResultatDiagnostic;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

#[AdminCrud(routePath: '/admin/resultat', routeName: 'admin_resultat')]
class ResultatDiagnosticCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResultatDiagnostic::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur'),
            IdField::new('id')->hideOnForm(),
            IntegerField::new('score', 'Score'),
            DateTimeField::new('createdAt', 'Date')->hideOnForm(),
        ];
    }
}
