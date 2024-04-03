<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('adress'),
            TextField::new('postal_code'),
            TextField::new('phoneNumber'),
            ChoiceField::new('roles')->setChoices([
                'USER' => 'ROLE_USER',
                'ADMIN' => 'ROLE_ADMIN',
                'STAFF' => 'ROLE_STAFF',
                'TEACHER' => 'ROLE_TEACHER',
            ])->allowMultipleChoices(),
        ];
    }
    
}
