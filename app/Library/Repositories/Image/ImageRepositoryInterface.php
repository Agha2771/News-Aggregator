<?php namespace News\Repositories\Image;


use News\Abstracts\RepositoryInterface;

interface ImageRepositoryInterface extends RepositoryInterface
{
    public function create($data);

    public function update($data,$id);

    public function search($data);
}
