<?php
// app/Repositories/Contracts/BaseRepositoryInterface.php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate($perPage = 15);
}
