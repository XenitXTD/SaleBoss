<?php namespace SaleBoss\Services\Validator;

class LetterValidator extends AbstractValidator {
	protected $rules = [
		'destination'     =>  'required',
		'folder'     =>  'required',
		'subject'       =>  'required',
	    'message'       =>  'required'
	];
} 