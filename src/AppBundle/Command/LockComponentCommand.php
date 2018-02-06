<?php

namespace AppBundle\Command;

use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Lock;
use Symfony\Component\Lock\Store\RedisStore;
use Symfony\Component\Lock\Store\RetryTillSaveStore;

class LockComponentCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:lock:component')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $predisClient = new Client([
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
        ]);

        $store      = new RedisStore($predisClient);
        $retryStore = new RetryTillSaveStore($store);
        $factory    = new Factory($retryStore);
        $lock = $factory->createLock('resource', 10.0);

        $output->writeln('*** START ***');

        $this->lockResource($lock, $output);

        $output->writeln('*** END ***');
    }

    /**
     * @param Lock $lock
     * @param OutputInterface $output
     */
    private function lockResource(Lock $lock, OutputInterface $output)
    {
        if ($lock->acquire(true)) {
            $this->processResource($output);

            $lock->release();
        } else {
            $output->writeln('<fg=red>Ohhhhh :(</>');
        }

    }

    /**
     * @param OutputInterface $output
     */
    private function processResource(OutputInterface $output)
    {
        $output->write('<info>ACQUIRE | </info>');

        for ($i = 0; $i < 15; $i++) {
            usleep(500000);
            $output->write('.');
        }

        $output->writeln('<fg=cyan> | RELEASE</>');
    }
}
