<?php

namespace Starkerxp\RabbitmqBundle\Command;

use Starkerxp\RabbitmqBundle\Command\Exception\NomChannelNonDefinitException;
use Starkerxp\RabbitmqBundle\Command\Exception\NomServiceNonDefinitException;
use Symfony\Component\Console\Input\InputOption;

abstract class ReceiverLockCommand extends LockCommand
{
    public function nomLocker()
    {
        return parent::nomLocker().'_'.$this->input->getOption('numeroScript');
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('numeroScript', 'n', InputOption::VALUE_OPTIONAL, 'Numéro de script', '1');
    }

    public function traitement()
    {
        $nomService = $this->getNomService();
        if (empty($nomService)) {
            throw new NomServiceNonDefinitException();
        }
        $nomChannel = $this->getNomChannel();
        if (empty($nomChannel)) {
            throw new NomChannelNonDefinitException();
        }
        $connection = $this->getContainer()->get('app.service.rabbitmq');
        $channel = $connection->channel();
        $channel->queue_declare($nomChannel, false, true, false, false);
        $callback = $this->getCallback();
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($nomChannel, '', false, false, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
        $this->output->writeln('<info>Execution terminé.</info>');
    }

    /**
     * @return string
     */
    abstract public function getNomChannel();

    /**
     * @return string
     */
    abstract public function getNomService();

    abstract public function getCallback();
}
