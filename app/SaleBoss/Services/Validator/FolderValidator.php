<?php

namespace SaleBoss\Services\Validator;


class FolderValidator extends AbstractValidator {
	protected $rules = [
		'name'     =>  'required',
	    'parent_id' => 'required|integer'
	];
} 