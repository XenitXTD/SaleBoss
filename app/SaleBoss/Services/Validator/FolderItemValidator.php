<?php

namespace SaleBoss\Services\Validator;


class FolderItemValidator extends AbstractValidator {
	protected $rules = [
		'name'     =>  'required',
	    'for_id' => 'required|integer',
	    'description' => 'required'
	];
} 