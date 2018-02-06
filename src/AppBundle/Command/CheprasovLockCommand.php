<?php

namespace AppBundle\Command;

use RedisClient\ClientFactory;
use RedisClient\RedisClient;
use RedisLock\RedisLock;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheprasovLockCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:lock:cheprasov')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $redisClient = ClientFactory::create([
            'server' => sprintf('%s://%s:%s', 'tcp', 'redis', 6379),
        ]);

        $redisLock = new RedisLock($redisClient, 'resource');

        $output->writeln('*** START ***');

        $this->lockResource($redisLock, $output);

        $output->writeln('*** END ***');
    }

    /**
     * @param RedisLock $redisLock
     * @param OutputInterface $output
     */
    private function lockResource(RedisLock $redisLock, OutputInterface $output)
    {
        if ($redisLock->acquire(10, 22)) {
            $this->processResource($output);

            $redisLock->release();
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
