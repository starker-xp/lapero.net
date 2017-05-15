<?php

namespace Starkerxp\UtilisateurBundle\Command;

use Starkerxp\StructureBundle\Command\LockCommand;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 15/05/2017
 * Time: 20:29
 */
class CreerUtilisateurCommand extends LockCommand
{
    protected function configure()
    {
        $this->setName('starkerxp:utilisateur:creer')
            ->addArgument('login', InputArgument::REQUIRED, "L'identifiant.")
            ->addArgument('password', InputArgument::REQUIRED, "Le mot de passe.")
            ->setDescription("Ajoute un nouvel utilisateur à la base de données");
    }

    public function traitement()
    {
        $user = Utilisateur::createFromPayload("chips@yopmail.com", ["roles"=>[]]);
        $user->setPlainPassword("test");
        $password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

}
