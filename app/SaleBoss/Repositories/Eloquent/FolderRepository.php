<?php namespace SaleBoss\Repositories\Eloquent;

use Illuminate\Support\Facades\Config;
use SaleBoss\Models\Folder;
use SaleBoss\Models\FolderItem;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Services\Upload\Facades\Upload;

class FolderRepository extends AbstractRepository implements FolderRepositoryInterface {

    protected $model;

    protected $modelItem;

    function __construct(
        Folder $folder,
        FolderItem $folderItem
    )
    {
        $this->model = $folder;
        $this->modelItem = $folderItem;
    }

    public function getList($model, $id)
    {
        $query = $this->model->newInstance();

        $list = $query->where('for_type', $model)->where('for_id', $id)->get();

        return $list;
    }

    public function getById($folderId)
    {
        return $this->model->findOrFail($folderId);
    }

    /**
     * @param $parent_id
     * @param $name
     * @param $creator_id
     * @param $for_type
     * @param $for_id
     * @return bool
     */
    public function addFolder($parent_id, $name, $creator_id, $for_type, $for_id)
    {
        $model = $this->model->newInstance();

        $model->parent_id = $parent_id;
        $model->name = $name;
        $model->creator_id = $creator_id;
        $model->for_type = $for_type;
        $model->for_id = $for_id;

        return $model->save();
    }

    public function addFolderItem($name, $creator_id, $for_id, $description, $file)
    {
        $model = $this->modelItem->newInstance();

        $model->name = $name;
        $model->creator_id = $creator_id;
        $model->for_id = json_encode(array_values($for_id));
        $model->description = $description;

        if($model->save())
        {
            if(!is_null($file))
            {
                Upload::doUpload($file,
                                 [
                                     'for_id'   => $model->id,
                                     'for_type' => Config::get('opilo_configs.notifications_types.FolderItem'),
                                     'path'     =>  'files/folders/items/'.$creator_id
                                 ]);
            }
        }

        return true;
    }

    public function getItemsList($userId, $folderId)
    {
        $query = $this->modelItem->newInstance();

        return $query->where('creator_id', $userId)->get();
    }

    public function getItemById($itemId)
    {
        return $this->modelItem->findOrFail($itemId);
    }

    public function getSearch($inputs, $userId)
    {
        $query = $this->modelItem->newInstance();

        if(!is_null($inputs))
        {
            foreach($inputs as $field => $value)
            {
                if(!is_null($field) and !empty($value) and ($field != 'for_id'))
                {
                    if($field == 'creator_id')
                        $query = $query->where($field, $value);
                    else
                        $query = $query->where($field, 'LIKE', '%' . $value . '%');
                }
            }
        } else
            $query = $query->where('creator_id', $userId);

        return $query->get();
    }
}
