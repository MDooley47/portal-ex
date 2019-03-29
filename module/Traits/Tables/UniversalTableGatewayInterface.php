<?php

namespace Traits\Tables;

interface UniversalTableGatewayInterface
{
    public function add($data);

    public function save($model);

    public function exists($id);

    public function get($id);

    public function all();

    public function delete($id);
}
