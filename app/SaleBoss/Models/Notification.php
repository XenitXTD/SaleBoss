<?php namespace SaleBoss\Models;

use Fenos\Notifynder\Models\Notification as ExNotification;
use Illuminate\Support\Facades\Event;

class Notification extends ExNotification {


    use DateTrait;

    /**
     * ExNotification has this method but this is optionaled.
     * It gives 'Not Read' notifications by user requests.
     * @param       $query
     * @param       $to_id
     * @param       $category
     * @param array $type
     * @param       $first_time
     * @param       $second_time
     * @param       $limit
     * @param       $paginate
     * @return mixed
     */
    public function scopeGetNotReadNotifications($query, $to_id, $category, array $type, $first_time, $second_time, $limit, $paginate)
    {
        if ( is_null($limit) )
        {
            if ( is_null($limit) )
            {
                return $query->with('body','from')
                             ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                             ->where('category_id','=', $category)
                             ->withNotRead()
                             ->orderBy('id','DSC')
                             ->get();
            } else
                return $query->with('body','from')
                                          ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                                          ->where('category_id','=', $category)
                                          ->whereBetween('created_at', [$first_time, $second_time])
                                          ->withNotRead()
                                          ->orderBy('id','DSC')
                                          ->get();
        }
        if ($paginate)
        {
            return $query->with('body','from')
                                      ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                                      ->where('category_id','=', $category)
                                      ->whereBetween('created_at', [$first_time, $second_time])
                                      ->withNotRead()
                                      ->orderBy('id','DSC')
                                      ->paginate($limit);
        }
        else
        {
            return $query->with('body','from')
                                      ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                                      ->where('category_id','=', $category)
                                      ->whereBetween('created_at', [$first_time, $second_time])
                                      ->withNotRead()
                                      ->orderBy('id','DSC')
                                      ->limit($limit)
                                      ->get()->parse();
        }

    }

	/**
     * ExNotification has this method but this is optionaled.
     * It gives 'All' notifications by user requests.
     * @param       $query
     * @param       $to_id
     * @param       $category
     * @param array $type
     * @param       $first_time
     * @param       $second_time
     * @param       $limit
     * @param       $paginate
     * @return mixed
     */
    public function scopeGetNotifications($query, $to_id, $category, array $type, $first_time, $second_time, $limit, $paginate)
    {
        if ( is_null($limit) )
        {
            if(is_null($first_time))
            {
                return $query->with('body','from')
                             ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                             ->where('category_id','=', $category)
                             ->orderBy('id','DSC')
                             ->get()->parse();
            } else
                return $query->with('body','from')
                             ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                             ->where('category_id','=', $category)
                             ->whereBetween('created_at', [$first_time, $second_time])
                             ->orderBy('id','DSC')
                             ->get()->parse();
        }
        if ($paginate)
        {
            return $query->with('body','from')
                         ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                         ->where('category_id','=', $category)
                         ->whereBetween('created_at', [$first_time, $second_time])
                         ->orderBy('id','DSC')
                         ->paginate($limit);
        }
        else
        {
            return $query->with('body','from')
                         ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                         ->where('category_id','=', $category)
                         ->whereBetween('created_at', [$first_time, $second_time])
                         ->orderBy('id','DSC')
                         ->limit($limit)
                         ->get()->parse();
        }
    }

	/**
     * ExNotification has this method but this is optionaled.
     * It changes all notifications to 'Read' by user request.
     * @param       $query
     * @param       $to_id
     * @param       $category
     * @param array $type
     * @param       $first_time
     * @param       $second_time
     * @return mixed
     */
    public function scopeSetReadNotifications($query, $to_id, $category, array $type, $from_id, $first_time, $second_time)
    {
        if(is_null($first_time)) {
            if ($from_id) {
                return $query->withNotRead()
                             ->wherePolymorphic('to_id', 'to_type', $to_id, $type['to_type'])
                             ->wherePolymorphic('from_id', 'from_type', $from_id, $type['from_type'])
                             ->where('category_id', '=', $category)
                             ->update(['read' => 1]);
            } else {
                return $query->withNotRead()
                             ->wherePolymorphic('to_id', 'to_type', $to_id, $type['to_type'])
                             ->where('category_id', '=', $category)
                             ->update(['read' => 1]);
            }
        } else
            if ($from_id) {
                return $query->withNotRead()
                             ->wherePolymorphic('to_id', 'to_type', $to_id, $type['to_type'])
                             ->wherePolymorphic('from_id', 'from_type', $from_id, $type['from_type'])
                             ->where('category_id', '=', $category)
                             ->whereBetween('created_at', [$first_time, $second_time])
                             ->update(['read' => 1]);
            } else {
                return $query->withNotRead()
                             ->wherePolymorphic('to_id', 'to_type', $to_id, $type['to_type'])
                             ->where('category_id', '=', $category)
                             ->whereBetween('created_at', [$first_time, $second_time])
                             ->update(['read' => 1]);
            }
    }

	/**
     * ExNotification has this method by this can delete notification by from id.
     * @param $query
     * @param $from_id
     * @return mixed
     */
    public static function scopeDeleteByFrom($query, $from_id)
    {
        return $query->where('from_id',$from_id)->delete();
    }

} 
