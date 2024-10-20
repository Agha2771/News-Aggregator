<?php namespace News\Abstracts;

abstract class EloquentRepository implements RepositoryInterface {
	protected $model;

	public function all ($fields =[]) {
		$fields = count($fields) ? $fields : '*' ;
		return $this->model->all($fields);
	}


	public function getById ( $id, array $with = [] ) {
		return $this->model->find( $id );
	}


	public function delete ( $id ) {
		return $this->model->where( 'id', $id )
						   ->delete();
	}
}
