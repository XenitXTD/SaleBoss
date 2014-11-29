<?php namespace SaleBoss\Models;

use Fenos\Notifynder\Models\Notification as ExNotification;
use Illuminate\Support\Facades\Event;

class Notification extends ExNotification {

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
            return $query->with('body','from')
                                      ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                                      ->where('category_id','=', $category)
                                      ->whereBetween('created_at', [$first_time, $second_time])
                                      ->withNotRead()
                                      ->orderBy('read','ASC')
                                      ->get()->parse();
        }
        if ($paginate)
        {
            return $query->with('body','from')
                                      ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                                      ->where('category_id','=', $category)
                                      ->whereBetween('created_at', [$first_time, $second_time])
                                      ->withNotRead()
                                      ->orderBy('read','ASC')
                                      ->paginate($limit);
        }
        else
        {
            return $query->with('body','from')
                                      ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                                      ->where('category_id','=', $category)
                                      ->whereBetween('created_at', [$first_time, $second_time])
                                      ->withNotRead()
                                      ->orderBy('read','ASC')
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
            return $query->with('body','from')
                         ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                         ->where('category_id','=', $category)
                         ->whereBetween('created_at', [$first_time, $second_time])
                         ->orderBy('read','ASC')
                         ->get()->parse();
        }
        if ($paginate)
        {
            return $query->with('body','from')
                         ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                         ->where('category_id','=', $category)
                         ->whereBetween('created_at', [$first_time, $second_time])
                         ->orderBy('read','ASC')
                         ->paginate($limit);
        }
        else
        {
            return $query->with('body','from')
                         ->wherePolymorphic('to_id','to_type',$to_id, $type['to_type'])
                         ->where('category_id','=', $category)
                         ->whereBetween('created_at', [$first_time, $second_time])
                         ->orderBy('read','ASC')
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
    public function scopeSetReadNotifications($query, $to_id, $category, array $type, $first_time, $second_time)
    {
        return $query->withNotRead()
                     ->wherePolymorphic('to_id','to_type', $to_id, $type['to_type'])
                     ->where('category_id','=',$category)
                     ->whereBetween('created_at', [$first_time, $second_time])
                     ->update(['read' => 1]);
    }
} 
