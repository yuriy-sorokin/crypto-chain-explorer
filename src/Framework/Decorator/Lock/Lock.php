<?php
declare(strict_types=1);

namespace App\Framework\Decorator\Lock;

use LogicException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\SharedLockInterface;

class Lock
{
    private readonly ?SharedLockInterface $lock;
    public function __construct(private readonly LockFactory $lockFactory) {}

    public function acquire(string $key): bool
    {
        $this->lock = $this->lockFactory->createLock($key);

        return $this->lock->acquire();
    }

    public function release(): void
    {
        if (false === isset($this->lock)) {
            throw new \LogicException('Cannot find lock. Did you call acquire() first?');
        }

        $this->lock->release();
    }
}
