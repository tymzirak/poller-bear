<?php

namespace App\Controller\Admin;

use App\Entity\UserInformation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserInformationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserInformation::class;
    }
}
