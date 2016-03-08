<?php

namespace Sectorr\Core\Contracts;

interface CrudContract
{
    public function _all();

    public function _find($id);

    public function _create(array $data);

    public function _update($id, array $data);

    public function _delete($id);
}
