<?php
return array(
	'panel_types'	=>	array(
		'عادی',
		'رایگان',
		'آزمایشی',
		'با تخفیف',
		'بدون پنل',
		'پنل دارد'
	),
	'payment_types'	=>	array(
		'آنلاین',
		'کارت به کارت',
		'کارتخوان',
		'نقدی'
	),
    'lead_status'   =>  array(
        '0'   =>  'نا مشخص',
        '1'   =>  'موفق',
        '-1'  =>  'نا موفق',
        '2'   =>  'بعدا مشخص میشود'
    ),
    'lead_limit'    =>  array(
        'each'      =>  array('with_status' => false, 'limit' => 5),
        'hourly'    =>  array('with_status' => true, 'limit' => 12),
        'daily'     =>  array('with_status' => true, 'limit' => 50)
    ),
    'lead_sortby'   =>  array(
        'created_at'    =>  'تاریخ ایجاد',
        'updated_at'    =>  'تاریخ بروز رسانی',
        'locked_at'     =>  'تاریخ قفل شدن',
        'priority'      =>  'اهمیت',
        'status'        =>  'وضعیت',
        'id'            =>  'شناسه'
    ),
    'order_sortby'   =>  array(
        'created_at'    =>  'تاریخ ایجاد',
        'updated_at'    =>  'تاریخ بروز رسانی',
        'id'            =>  'شناسه'
    ),
    'workflow_states'    =>  array(
        '1'     =>  'فروش',
        '2'     =>  'حسابداری',
        '3'     =>  'پشتیبانی'
    ),
    'user_sortby'      =>  array(
        'created_at'        =>  'تاریخ ایجاد',
        'updated_at'        =>  'تاریخ بروز رسانی',
        'id'                =>  'شناسه'
    ),
    'notifications_categories'  =>  array(
        'Leads' => 'Leads',
        'Users' =>  'Users',
        'TodayLeads' => 'TodayLeads'
    ),
    'notifications_types'  =>  array(
        'Lead' => 'SaleBoss\Models\Lead',
        'User' =>  'SaleBoss\Models\User'
    ),

);