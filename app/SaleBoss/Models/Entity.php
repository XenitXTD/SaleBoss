<?php

namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Miladr\Jalali\jDate;

class Entity extends Eloquent {

	use SoftDeletingTrait;
	use DateTrait;
	use ChartTrait;

	protected $table = 'entities';

	/**
	 * Relation between Entity Types and Entities
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function entityType()
	{
		return $this->belongsTo('SaleBoss\Models\EntityType','entity_type_id');
	}

	/**
	 * One to many relation ship with Entities and values
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function entityValues()
	{
		return $this->hasMany('SaleBoss\Models\Value','entity_id');
	}

	public function diff()
	{
		return jDate::forge($this->created_at)->ago();
	}
} 