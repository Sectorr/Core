<?php

namespace Sectorr\Core\Database;

use Sectorr\Core\Config;
use Sectorr\Core\Contracts\CrudContract;

abstract class Model implements CrudContract
{

    protected $db;
    protected $where = ["AND" => []];

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Find item by ID.
     *
     * @param $id
     * @return mixed
     */
    public function _find($id)
    {
        return $this->db->get($this->table, '*', ['id' => $id]);
    }

    /**
     * Adds a where statement to the current query.
     *
     * @param $field
     * @param $input
     * @return $this
     */
    public function _where($field, $input)
    {
        $this->where['AND'][$field] = $input;
        return $this;
    }

    /**
     * Get's the first result.
     *
     * @param string $columns
     * @return bool
     */
    public function _first($columns = '*')
    {
        return $this->db->get($this->table, $columns, $this->where);
    }

    /**
     * Get all results filtered on set where.
     *
     * @param string $columns
     * @return array|bool
     */
    public function _get($columns = '*')
    {
        return $this->db->select($this->table, $columns, $this->where);
    }

    /**
     * Get all results from table.
     *
     * @param string $columns
     * @return array|bool
     */
    public function _all($columns = '*')
    {
        return $this->db->select($this->table, $columns);
    }

    /**
     * Insert new item into table.
     *
     * @param array $data
     * @return array
     */
    public function _create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Updates item in table.
     *
     * @param $id
     * @param array $data
     * @return bool|int
     */
    public function _update($id, array $data)
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    /**
     * Deletes item from table.
     *
     * @param $id
     * @return bool|int
     */
    public function _delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Call method normally.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, '_' . $name], $arguments);
    }

    /**
     * Call method statically.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([(new static()), '_' . $name], $arguments);
    }
}
