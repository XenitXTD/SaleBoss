<?php

namespace SaleBoss\Services\ViewBuilder\Builders;


use Illuminate\Support\Facades\URL;
use SaleBoss\Services\ViewBuilder\Builder;
use SaleBoss\Services\ViewBuilder\BuilderInterface;

class FolderBuilder extends Builder implements BuilderInterface {

    public function FolderTreeViewWithCheckBox($collection, $currentParent = 0 , $currLevel = 0, $prevLevel = -1)
    {

        foreach ($collection as $object) {

            if ($currentParent == $object->parent_id) {

                if ($currLevel > $prevLevel) $this->output .= ' <ol class="dd-list">';

                if ($currLevel == $prevLevel) $this->output .= " </li> ";

                $this->output .= '<li class="dd-item"><div class="dd-handle"><input type="checkbox" class="ace" name="item[for_id][]" value="'.$object->id.'"><span class="lbl"> '.$object->name .'</span></div>';

                if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }

                $currLevel++;

                $this->FolderTreeViewWithCheckBox($collection, $object->id, $currLevel, $prevLevel);

                $currLevel--;

            }

        }

        if ($currLevel == $prevLevel) $this->output .= " </li>  </ol> ";

        return $this->output;
    }


    public function FolderSelectView($collection, $parent = 0, $level = 0)
    {
        foreach($collection as $menu){
            if($menu['parent_id'] == $parent)
            {
                $this->output .= '<option value="'.$menu['id'].'">'.str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$level).$menu['name'].'</option>';
                $this->FolderSelectView($collection, $menu['id'], $level+1);
            }
        }

        return $this->output;
    }

    public function FolderUserTreeView($collection, $currentParent = 0 , $currLevel = 0, $prevLevel = -1)
    {

        foreach ($collection as $object) {

            if ($currentParent == $object->parent_id) {

                if ($currLevel > $prevLevel) $this->output .= ' <ol class="dd-list">';

                if ($currLevel == $prevLevel) $this->output .= " </li> ";

                $this->output .= '<li class="dd-item"><div class="dd-handle"><a href="'.URL::route('FolderIndex').'/'.$object->id.'/items">'.$object->name .'</a>
                                    <div class="pull-left action-buttons">
                                        <a class="blue" href="'.URL::route('FolderIndex').'/edit/'.$object->id.'">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>

                                        <a class="red" href="'.URL::route('FolderIndex').'/delete/'.$object->id.'">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </a>
                                    </div>
                        </div>';

                if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }

                $currLevel++;

                $this->FolderUserTreeView($collection, $object->id, $currLevel, $prevLevel);

                $currLevel--;

            }

        }

        if ($currLevel == $prevLevel) $this->output .= " </li>  </ol> ";

        return $this->output;
    }


    public function FolderTreeView($collection, $currentParent = 0 , $currLevel = 0, $prevLevel = -1)
    {

        foreach ($collection as $object) {

            if ($currentParent == $object->parent_id) {

                if ($currLevel > $prevLevel) $this->output .= ' <ol class="dd-list">';

                if ($currLevel == $prevLevel) $this->output .= " </li> ";

                $this->output .= '<li class="dd-item"><div class="dd-handle"><a href="'.URL::route('FolderIndex').'/'.$object->id.'">'.$object->name .'</a>
                                    <div class="pull-left action-buttons">
                                        <a class="blue" href="'.URL::route('FolderIndex').'/edit/'.$object->id.'">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>

                                        <a class="red" href="'.URL::route('FolderIndex').'/delete/'.$object->id.'">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </a>
                                    </div>
                        </div>';

                if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }

                $currLevel++;

                $this->FolderTreeView($collection, $object->id, $currLevel, $prevLevel);

                $currLevel--;

            }

        }

        if ($currLevel == $prevLevel) $this->output .= " </li>  </ol> ";

        return $this->output;
    }

}