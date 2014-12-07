<?php

namespace SaleBoss\Services\Validator;


class TaskValidator extends AbstractValidator {
	protected $rules = [
		'description'     =>  'required',
		'todo_at'       =>  'required'
	];
} 