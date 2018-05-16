<?php

namespace App\Repositories\Eloquent;

use App\Models\CompressedCopy;
use App\Repositories\Contracts\CompressedCopyRepositoryInterface;

class CompressedCopyRepository extends AbstractBaseRepository implements CompressedCopyRepositoryInterface
{
    /**
     * The eloquent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * CompressedCopyRepository constructor.
     *
     * @param \App\Models\CompressedCopy|null $compressedCopy
     *
     * @return void
     */
    public function __construct(CompressedCopy $compressedCopy = null)
    {
        $this->model = $compressedCopy ?? app(CompressedCopy::class);
    }
}
