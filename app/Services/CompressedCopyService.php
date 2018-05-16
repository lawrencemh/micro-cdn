<?php

namespace App\Services;

use App\Repositories\Contracts\CompressedCopyRepositoryInterface;
use App\Exceptions\Services\Media\FailedToRemoveFromStorageException;

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
     *
     * @return void
     */
    public function __construct(CompressedCopyRepositoryInterface $compressedCopyRepository = null)
    {
        $this->repository = $compressedCopyRepository ?? app(CompressedCopyRepositoryInterface::class);
    }

    /**
     * Delete the given entity id using the repository.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(int $id)
    {
        $copy = $this->findById($id);

        app('db')->transaction(function () use ($copy) {
            $this->repository->destroy($copy->id);

            // Physically remove file from storage
            if (file_exists($copy->getFullLocalPath()) && unlink($copy->getFullLocalPath()) === false) {
                throw new FailedToRemoveFromStorageException('Failed to remove the file from storage');
            }
        });

        return $copy;
    }
}
