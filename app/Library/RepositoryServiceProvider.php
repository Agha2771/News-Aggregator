<?php namespace News;

use Illuminate\Support\ServiceProvider;
use News\Repositories\Article\ArticleEloquentRepository;
use News\Repositories\Article\ArticleRepositoryInterface;
use News\Repositories\User\UserEloquentRepository;
use News\Repositories\User\UserRepositoryInterface;

use News\Repositories\Preference\PreferenceRepositoryInterface;
use News\Repositories\Preference\PreferenceEloquentRepository;


class RepositoryServiceProvider extends ServiceProvider {

	public function register () {

		$bindings = [
			[ UserRepositoryInterface::class, UserEloquentRepository::class ],
			[ ArticleRepositoryInterface::class, ArticleEloquentRepository::class ],
			[ PreferenceRepositoryInterface::class, PreferenceEloquentRepository::class ],

		];
		$this->bindInterfacesWithTheirImplementations( $bindings );
	}

	public function bindInterfacesWithTheirImplementations ( $bindings ) {
		foreach ( $bindings as $binding ) {

		    $this->app->bind( $binding[ 0 ], $binding[ 1 ] );
		}

	}
}
