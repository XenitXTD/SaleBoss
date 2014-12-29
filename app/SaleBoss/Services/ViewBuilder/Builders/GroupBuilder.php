<?php

namespace SaleBoss\Services\ViewBuilder\Builders;


use SaleBoss\Services\ViewBuilder\Builder;
use SaleBoss\Services\ViewBuilder\BuilderInterface;

class GroupBuilder extends Builder implements BuilderInterface {

    public function groupSelectView($collection, $parent = Null, $level = 0)
    {
        foreach($collection as $group){
            if($group['parent_id'] == $parent)
            {
                $this->output .= '<option value="'.$group['id'].'">'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$level).$group['display_name'].'</option>';
                $this->GroupSelectView($collection, $group['id'], $level+1);
            }
        }

        return $this->output;
    }

}