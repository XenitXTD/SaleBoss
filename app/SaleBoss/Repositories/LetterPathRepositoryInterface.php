<?php namespace SaleBoss\Repositories;

interface LetterPathRepositoryInterface {

    public function addLetterPath($letterId, $path, $pathLog);

    public function findByIdAndDestination($letterId, $destinationId);
}