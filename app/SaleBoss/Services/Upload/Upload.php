<?php
namespace SaleBoss\Services\Upload;

use SaleBoss\Repositories\UploadRepositoryInterface;

class Upload implements UploadInterface {

    public $uploadRepo;

    function __construct(
        UploadRepositoryInterface $uploadRepositoryInterface
    )
    {
        $this->uploadRepo = $uploadRepositoryInterface;
    }

    public function doUpload($file, array $data)
    {
        $destinationPath = $data['path'];
        $filename        = $data['for_id'] . '_' . str_random(6) . '.' . $file->guessExtension();
        $uploadSuccess   = $file->move($destinationPath, $filename);

        if($uploadSuccess)
        {
            return $this->uploadRepo->rawFileName($filename, $data);
        }

    }


}