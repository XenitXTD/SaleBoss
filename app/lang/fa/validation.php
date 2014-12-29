<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"         => "The :attribute must be accepted.",
	"active_url"       => "The :attribute is not a valid URL.",
	"after"            => "The :attribute must be a date after :date.",
	"alpha"            => "The :attribute may only contain letters.",
	"alpha_dash"       => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"        => "The :attribute may only contain letters and numbers.",
	"array"            => "The :attribute must be an array.",
	"before"           => "The :attribute must be a date before :date.",
	"between"          => array(
		"numeric" => "فیلد :attribute باید بین :min و :max. باشد",
		"file"    => "The :attribute must be between :min and :max kilobytes.",
		"string"  => "The :attribute must be between :min and :max characters.",
		"array"   => "The :attribute must have between :min and :max items.",
	),
	"confirmed"        => "فیلد :attribute باید دارای تاییدیه باشد.",
	"date"             => "The :attribute is not a valid date.",
	"date_format"      => "The :attribute does not match the format :format.",
	"different"        => "The :attribute and :other must be different.",
	"digits"           => "فیلد :attribute باید :digits کارکتر داشته باشد.",
	"digits_between"   => "فیلد :attribute باید بین :min و :max کارکتر باشد.",
	"email"            => "The :attribute format is invalid.",
	"exists"           => "The selected :attribute is invalid.",
	"image"            => "The :attribute must be an image.",
	"in"               => "The selected :attribute is invalid.",
	"integer"          => "فیلد  :attribute باید عدد باشد.",
	"ip"               => "The :attribute must be a valid IP address.",
	"max"              => array(
		"numeric" => "The :attribute may not be greater than :max.",
		"file"    => "The :attribute may not be greater than :max kilobytes.",
		"string"  => "The :attribute may not be greater than :max characters.",
		"array"   => "The :attribute may not have more than :max items.",
	),
	"mimes"            => "The :attribute must be a file of type: :values.",
	"min"              => array(
		"numeric" => "The :attribute must be at least :min.",
		"file"    => "The :attribute must be at least :min kilobytes.",
		"string"  => "فیلد :attribute باید حداقل :min کارکتر باشد.",
		"array"   => "The :attribute must have at least :min items.",
	),
	"not_in"           => " فیلد :attribute باید دارای انتخاب مشخص باشد.",
	"numeric"          => "فیلد :attribute میبایست عدد باشد.",
	"regex"            => "فیلد :attribute اشتباه است",
	"required"         => "فیلد :attribute باید دارای مقدار باشد.",
	"required_if"      => "The :attribute field is required when :other is :value.",
	"required_with"    => "فیلد :attribute ضروریست زمانی که :values دارای مقدار میباشد.",
	"required_without" => "The :attribute field is required when :values is not present.",
	"same"             => "The :attribute and :other must match.",
	"size"             => array(
		"numeric" => "The :attribute must be :size.",
		"file"    => "The :attribute must be :size kilobytes.",
		"string"  => "The :attribute must be :size characters.",
		"array"   => "The :attribute must contain :size items.",
	),
	"unique"           => "فیلد :attribute که وارد کردید قبلا گرفته شده است.",
	"url"              => "The :attribute format is invalid.",
	"unique_update"				   => "The :attribute has been already taken.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(
			'email'					=>  '<em><b> ایمیل </b></em>',
			'password'				=>  '<em><b> پسورد </b></em>',
			'auction_title'			=>  '<em><b> نام مناقصه </b></em>',
			'auction_description'	=>  '<em><b>  توضیحات مناقصه </b></em>',
			'category_name'			=>  '<em><b> نام برچسب </b></em>',
			'display_name'          =>  '<em><b> نام </b></em>',
			'machine_name'          =>  '<em><b> نام ماشینی</b></em>',
			'first_name'            =>  '<em><b>نام</b></em>',
			'last_name'             =>  '<em><b>نام خانوادگی</b></em>',
			'mobile'                =>  '<em><b>موبایل</b></em>',
			'tell'                  =>  '<em><b>تلفن</b></em>',
			'national_code'         =>  '<em><b>کدملی</b></em>',
			'name'                  =>  '<em><b>نام</b></em>',
            'password_confirmation' =>  '<em><b>تایید پسورد</b></em>',
            'old_password'          =>  '<em><b>پسورد قدیمی</b></em>',
			'description'           =>  '<em><b>توضیحات</b></em>',
			'phone'                 =>  '<em><b>شماره</b></em>',
	        'tag'                   =>  '<em><b>زمینه فعالیت</b></em>',
	        'remind_at'             =>  '<em><b>به یاد آوری</b></em>',
	        'todo_at'               =>  '<em><b>زمان اجرا</b></em>',
	        'subject'               =>  '<em><b>موضوع</b></em>',
	        'message'               =>  '<em><b>متن</b></em>',
	        'destination'                  =>  '<em><b>دریافت کننده</b></em>',
	        'folder'                  =>  '<em><b>پوشه بایگانی</b></em>',
	        'for_id'                  =>  '<em><b>پوشه بایگانی</b></em>'
		),

);
