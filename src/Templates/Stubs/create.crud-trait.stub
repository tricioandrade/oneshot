<?php

namespace App\Traits\Essentials\Database;

use App\Exceptions\DatabaseException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait CrudTrait
{
    private array | string $relations;
    private Model | Builder $model;

    /**
     * Get all data from database
     *
     * @return mixed
     * @throws DatabaseException
     */
    private function getAllData(): mixed
    {
        $resource = $this->model::withTrashed()->with($this->relations)->get();
        return $this->isResource($resource);
    }

    /**
     * Get data by ID
     *
     * @param int $id
     * @return mixed
     * @throws DatabaseException
     */
    private function getByIdentity(int $id): mixed
    {
        $resource = $this->model::withTrashed()->with($this->relations)->find($id);
        return $this->isResource($resource);
    }

    /**
     * Get data by slug
     *
     * @param string $slug
     * @return mixed
     * @throws DatabaseException
     */
    private function getBySlugInfo(string $slug): mixed
    {
        $resource = $this->model::withTrashed()->with($this->relations)->where('slug', $slug)->get();
        return $this->isResource($resource);
    }

    /**
     * Get data by anonymous row
     *
     * @param string $anonymousRow
     * @param $value
     * @return mixed
     * @throws DatabaseException
     */
    private function getByAnonymousInfo(string $anonymousRow, $value): mixed
    {
        $resource = $this->model::withTrashed()->with($this->relations)->where($anonymousRow, $value)->get();
        return $this->isResource($resource);
    }

    /**
     * Create data
     *
     * @param array $attributes
     * @return mixed
     * @throws DatabaseException
     */
    private function createData(array $attributes): mixed
    {
        $create = $this->model::create($attributes);
        return $this->isResource($create->load($this->relations));
    }

    /**
     * Update data
     *
     * @param int $id
     * @param array $attributes
     * @return mixed
     * @throws DatabaseException
     */
    private function updateData(array $attributes, int $id): mixed
    {
        $update = $this->getByIdentity($id);
        $update->update($attributes);

        return $this->isResource($update->load($this->relations));
    }

    /**
     * Delete data | put on trash
     *
     * @param int $id
     * @return mixed
     * @throws DatabaseException
     */
    private function deleteData(int $id): mixed
    {
        $target = $this->getByIdentity($id);
        return $this->isResource($target->delete($id));
    }

    /**
     * Delete data permanently
     *
     * @param int $id
     * @return mixed
     * @throws DatabaseException
     */
    private function forceDeleteData(int $id): mixed
    {
        $target = $this->getByIdentity($id);
        return $this->isResource($target->forceDelete($id));
    }

    /**
     * Restore data from database
     *
     * @param int $id
     * @return mixed
     * @throws DatabaseException
     */
    private function restoreData(int $id): mixed
    {
        $target = $this->getByIdentity($id);
        return $this->isResource($target->restore($id));
    }

    /**
     * Throws database exception if resource not found
     *
     * @param mixed $resource
     * @return mixed
     * @throws DatabaseException
     */
    private function isResource(mixed $resource): mixed
    {
        return $resource ?? throw new \App\Exceptions\DatabaseException('Resource not found');
    }
}
