<?php namespace SaleBoss\Repositories\Eloquent;

use SaleBoss\Models\Upload;
use SaleBoss\Repositories\UploadRepositoryInterface;

class UploadRepository extends AbstractRepository implements UploadRepositoryInterface {

    protected $model;

    function __construct(Upload $upload)
    {
        $this->model = $upload;
    }

    public function rawFileName($fileName, array $data)
    {
        $model = $this->model->newInstance();

        $model->name = $fileName;
        $model->for_id = $data['for_id'];
        $model->for_type = $data['for_type'];
        $model->path = $data['path'];

        return $model->save();

    }
}