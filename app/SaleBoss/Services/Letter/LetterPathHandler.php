<?php
namespace SaleBoss\Services\Letter;


use SaleBoss\Repositories\GroupRepositoryInterface;

class LetterPathHandler {

    private $xArray = [];

    private $yArray = [];

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepo;

    function __construct(
        GroupRepositoryInterface $groupRepository
    )
    {
        $this->groupRepo = $groupRepository;
    }

    /**
     * @param $start
     * @param $destination
     * @return array
     */
    public function createPath($start, $destination)
    {
        array_push($this->xArray, $start);
        array_push($this->yArray, $destination);

        $x = !is_null($start) ? ( $this->groupRepo->findById($start)->parent_id ?: null ) : null;
        $y = !is_null($destination) ? ( $this->groupRepo->findById($destination)->parent_id ?: null ) : null;

        if($x == $y)
        {
            $this->setArray($this->xArray, $x);
            $this->setArray($this->yArray, $y);
            return $this->setPath($this->xArray, array_reverse($this->yArray));
        } else
            $this->setArray($this->xArray, $x);
            $this->setArray($this->yArray, $y);
            return $this->createPath($x, $y);
    }

    private function setArray($array, $id)
    {
        if(!in_array($id, $array))
        {
            return array_push($array, $id);
        } else
            return $array;
    }

    private function setPath($xArray, $yArray)
    {
        $array = array_merge($xArray, $yArray);
        return array_filter(array_unique($array));
    }

    public function resetOutput()
    {
        $this->xArray = [];

        $this->yArray = [];

        return $this;
    }
}