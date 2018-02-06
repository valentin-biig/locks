<?php

namespace AppBundle\Command;

use NinjaMutex\Lock\PredisRedisLock;
use NinjaMutex\Mutex;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NinjaMutexCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:lock:ninja')
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

        $redisLock = new PredisRedisLock($predisClient);
        $mutex     = new Mutex('resource', $redisLock);

        $output->writeln('*** START ***');

        $this->lockResource($mutex, $output);

        $output->writeln('*** END ***');
    }

    /**
     * @param Mutex $mutex
     * @param OutputInterface $output
     */
    private function lockResource(Mutex $mutex, OutputInterface $output)
    {
        if ($mutex->acquireLock(6000)) {
            $this->processResource($output);

            $mutex->releaseLock();
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
