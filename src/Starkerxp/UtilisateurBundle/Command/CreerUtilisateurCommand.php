<?php

namespace Starkerxp\UtilisateurBundle\Command;

use Starkerxp\StructureBundle\Command\LockCommand;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Console\Input\InputArgument;


class CreerUtilisateurCommand extends LockCommand
{
    public function traitement()
    {
        $user = Utilisateur::createFromPayload($this->input->getArgument("login"), ["roles" => ["ROLE_SUPER_ADMIN"]]);
        $user->setPlainPassword($this->input->getArgument("password"));
        $password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    protected function configure()
    {
        $this->setName('starkerxp:utilisateur:creer')
            ->addArgument('login', InputArgument::REQUIRED, "L'identifiant.")
            ->addArgument('password', InputArgument::REQUIRED, "Le mot de passe.")
            ->setDescription("Ajoute un nouvel utilisateur à la base de données");
    }

}
