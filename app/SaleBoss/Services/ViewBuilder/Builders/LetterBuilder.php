<?php

namespace SaleBoss\Services\ViewBuilder\Builders;


use SaleBoss\Services\ViewBuilder\Builder;
use SaleBoss\Services\ViewBuilder\BuilderInterface;

class LetterBuilder extends Builder implements BuilderInterface {


    public function LetterDestinationTreeViewWithCheckBox($collection, $currentParent = 0 , $currLevel = 0, $prevLevel = -1)
    {

        foreach ($collection as $object) {

            if ($currentParent == $object->parent_id) {

                if ($currLevel > $prevLevel) $this->output .= ' <ol class="dd-list">';

                if ($currLevel == $prevLevel) $this->output .= " </li> ";

                $this->output .= '<li class="dd-item"><div class="dd-handle"><input type="checkbox" class="ace" name="item[destination][]" value="'.$object->id.'"><span class="lbl"> '.$object->display_name .'</span></div>';

                if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }

                $currLevel++;

                $this->LetterDestinationTreeViewWithCheckBox($collection, $object->id, $currLevel, $prevLevel);

                $currLevel--;

            }

        }

        if ($currLevel == $prevLevel) $this->output .= " </li>  </ol> ";

        return $this->output;
    }

}