<?php

namespace Starkerxp\RabbitmqBundle\Command;

use PhpAmqpLib\Exception\AMQPTimeoutException;
use Starkerxp\RabbitmqBundle\Command\Exception\NomChannelNonDefinitException;
use Starkerxp\RabbitmqBundle\Command\Exception\NomServiceNonDefinitException;
use Starkerxp\StructureBundle\Command\LockCommand;
use Symfony\Component\Console\Input\InputOption;

abstract class ReceiverLockCommand extends LockCommand
{

    public static function getPushChannel()
    {
        return (self::getDelaiTemporisation() > 0 ? 'tmp_exchange_' : '').self::getNomChannel();
    }

    protected function getDelaiTemporisation()
    {
        return 0;
    }

    /**
     * @return string
     */
    abstract public function getNomChannel();

    public function nomLocker()
    {
        return parent::nomLocker().'_'.$this->input->getOption('numeroScript');
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
        $connection = $this->getContainer()->get('starkerxp_rabbitmq.service.rabbitmq');
        $channel = $connection->channel();
        $channel->queue_declare($nomChannel, false, true, false, false);

        if ($this->getDelaiTemporisation() > 0) {
            $channel->exchange_declare('exchange_'.$nomChannel, 'direct');
            $channel->queue_bind($nomChannel, 'exchange_'.$nomChannel);
            $channel->queue_declare(
                "tmp_".$nomChannel,
                false,
                false,
                false,
                true,
                true,
                [
                    'x-message-ttl'          => ['I', $this->getDelaiTemporisation() * 1000],   // delay in seconds to milliseconds
                    "x-expires"              => ["I", $this->getDelaiTemporisation() * 1000 + 1000],
                    'x-dead-letter-exchange' => ['S', 'exchange_'.$nomChannel] // after message expiration in delay queue, move message to the right.now.queue
                ]
            );
            $channel->exchange_declare('tmp_exchange_'.$nomChannel, 'direct');
            $channel->queue_bind('tmp_'.$nomChannel, 'tmp_exchange_'.$nomChannel);
        }

        $callback = $this->getCallback();
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($nomChannel, '', false, false, false, false, $callback);
        try {
            while (count($channel->callbacks)) {
                $channel->wait(null, false, $this->input->getOption('timeout'));
            }
        } catch (AMQPTimeoutException $e) {
        }
        $channel->close();
        $connection->close();
        $this->output->writeln('<info>Execution terminé.</info>');
    }

    /**
     * @return string
     */
    abstract public function getNomService();

    abstract public function getCallback();

    protected function configure()
    {
        parent::configure();
        $this->addOption('numeroScript', 'n', InputOption::VALUE_OPTIONAL, 'Numéro de script', 1);
        $this->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'Timeout callback queue', 0);
    }


}
