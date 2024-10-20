<?php namespace Image;

use Illuminate\Support\ServiceProvider;
use News\Repositories\News\ImageEloquentRepository;
use News\Repositories\News\ImageRepositoryInterface;
use News\Repositories\User\UserEloquentRepository;
use News\Repositories\User\UserRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider {

	public function register () {

		$bindings = [
			[ UserRepositoryInterface::class, UserEloquentRepository::class ],
			[ ImageRepositoryInterface::class, ImageEloquentRepository::class ],

		];
		$this->bindInterfacesWithTheirImplementations( $bindings );
	}

	public function bindInterfacesWithTheirImplementations ( $bindings ) {
		foreach ( $bindings as $binding ) {

		    $this->app->bind( $binding[ 0 ], $binding[ 1 ] );
		}

	}
}
