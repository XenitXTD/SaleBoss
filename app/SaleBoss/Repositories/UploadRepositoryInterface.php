<?php namespace SaleBoss\Repositories;

interface UploadRepositoryInterface {

    public function rawFileName($filename, array $data);
}