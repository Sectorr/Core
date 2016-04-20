<?php

namespace Sectorr\Core\Database;

use Sectorr\Core\Config;
use Sectorr\Core\Contracts\CrudContract;

abstract class Model
{

    protected $db;
    protected $where = ["AND" => []];
    private $fields = [];

    /**
     * Model constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields = [])
    {
        $this->db = new Database();
        $this->setProperties($fields);
    }

    /**
     * Find item by ID.
     *
     * @param $id
     * @return mixed
     */
    protected function _find($id)
    {
        $data = $this->db->get($this->table, '*', ['id' => $id]);

        if (empty($data)) {
            return false;
        }

        return $this->setProperties($data);
    }

    /**
     * Adds a where statement to the current query.
     *
     * @param $field
     * @param $input
     * @return $this
     */
    protected function _where($field, $input)
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
    protected function _first($columns = '*')
    {
        $data = $this->db->get($this->table, $columns, $this->where);

        if (empty($data)) {
            return false;
        }

        return $this->setProperties($data);
    }

    /**
     * Get all results filtered on set where.
     *
     * @param string $columns
     * @return array|bool
     */
    protected function _get($columns = '*')
    {
        $data = $this->db->select($this->table, $columns, $this->where);

        if (empty($data)) {
            return false;
        }

        return $this->getModelObjects($data);
    }

    /**
     * Get all results from table.
     *
     * @param string $columns
     * @return array|bool
     */
    protected function _all($columns = '*')
    {
        $data = $this->db->select($this->table, $columns);

        if (empty($data)) {
            return false;
        }

        return $this->getModelObjects($data);
    }

    /**
     * Insert new item into table.
     *
     * @param array $data
     * @return array
     */
    protected function _create(array $data)
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
    protected function _update($id, array $data)
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    /**
     * Deletes item from table.
     *
     * @param $id
     * @return bool|int
     */
    protected function _delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     *
     *
     * @return $this
     */
    public function save()
    {
        if (isset($this->fields['id'])) {
            return $this->_update($this->fields['id'], $this->fields);

            return $this;
        }

        $this->id = $this->_create($this->fields);

        return $this;
    }

    public function getModelObjects(array $results)
    {
        foreach ($results as $key => $result) {
            $results[$key] = new $this($result);
        }

        return $results;
    }

    /**
     * Get dynamic property.
     *
     * @param $key
     * @return null
     */
    private function getProperty($key)
    {
        return array_key_exists($key, $this->fields) ? $this->fields[$key] : null;
    }

    /**
     * Set dynamic property.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    private function setProperty($key, $value)
    {
        $this->fields[$key] = $value;

        return $this;
    }

    /**
     * Go through array and set all dynamic properties.
     *
     * @param $data
     * @return $this
     */
    private function setProperties(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setProperty($key, $value);
        }

        return $this;
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

    /**
     * Get dynamic property from object.
     *
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        return $this->getProperty($key);
    }

    /**
     * Set dynamic property from object.
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->setProperty($key, $value);
    }
}
