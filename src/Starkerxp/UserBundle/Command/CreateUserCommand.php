<?php

namespace Starkerxp\UserBundle\Command;

use Starkerxp\StructureBundle\Command\LockCommand;
use Starkerxp\UserBundle\Entity\User;
use Symfony\Component\Console\Input\InputArgument;


class CreateUserCommand extends LockCommand
{
    public function treatment()
    {
        $user = User::createFromPayload($this->input->getArgument("login"), ["roles" => ["ROLE_SUPER_ADMIN"]]);
        $user->setPlainPassword($this->input->getArgument("password"));
        $password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    protected function configure()
    {
        $this->setName('starkerxp:user:creer')
            ->addArgument('login', InputArgument::REQUIRED, "L'identifiant.")
            ->addArgument('password', InputArgument::REQUIRED, "Le mot de passe.")
            ->setDescription("Ajoute un nouvel user à la base de données");
    }

}
