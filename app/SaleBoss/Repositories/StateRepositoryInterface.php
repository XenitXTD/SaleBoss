<?php namespace SaleBoss\Repositories;

interface StateRepositoryInterface {

	/**
	 * Updating an State in DB
	 *
	 * @param $id
	 * @param $input
	 * @return \SaleBoss\Models\State
	 */
	public function update($id, $input);

	/**
	 * Saving an State in DB
	 *
	 * @param $input
	 * @return \SaleBoss\Models\State
	 */
	public function create($input);

	/**
	 * Listing State Collection
	 *
	 * @return Collection
	 */
	public function getAll();

	/**
	 * Finding an State by its id
	 *
	 * @param $id
	 * @return \SaleBoss\Models\State
	 */
	public function findById($id);

	/**
	 * Get all sates sorted in a key
	 *
	 * @param $field
	 * @param string $sort
	 * @return Collection
	 */
	public function getAllSorted($field , $sort = 'asc');

    public function findByPriority($int);
}