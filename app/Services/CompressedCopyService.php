<?php

namespace App\Services;

use App\Repositories\Contracts\CompressedCopyRepositoryInterface;

class CompressedCopyService extends AbstractBaseService
{
    /**
     * The repository instance.
     *
     * @var \App\Repositories\Contracts\BaseRepositoryInterface
     */
    protected $repository;

    /**
     * CompressedCopyService constructor.
     *
     * @param \App\Repositories\Contracts\CompressedCopyRepositoryInterface $compressedCopyRepository
     * @return void
     */
    function __construct(CompressedCopyRepositoryInterface $compressedCopyRepository = null)
    {
        $this->repository = $compressedCopyRepository ?? app(CompressedCopyRepositoryInterface::class);
    }
}
