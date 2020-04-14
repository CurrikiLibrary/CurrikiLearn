<?php

namespace App\Contracts\Repositories;

interface BaseRepositoryInterface
{
    public function all($columns = array('*'));

    public function paginate($perPage = 15, $columns = array('*'));

    public function create(array $data);

    public function update(array $data, $id);

    public function destroy($id);

    public function delete($attribute, $value);

    public function find($id, $columns = array('*'));

    public function findBy($field, $value, $columns = array('*'));
}