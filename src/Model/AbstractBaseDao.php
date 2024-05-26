<?php

namespace App\Model;

use Doctrine\DBAL\Exception;
use Pimcore\Model\Dao\AbstractDao;
use Pimcore\Model\Exception\NotFoundException;

abstract class AbstractBaseDao extends AbstractDao
{
    abstract public function getTableName(): string;

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function getById(int $id): void
    {
        $data = $this->db->fetchAssociative('SELECT * FROM ' . $this->getTableName() . ' WHERE `id` = ?', [$id]);

        if (!$data) {
            throw new NotFoundException(sprintf('Unable to load site with ID `%s`', $id));
        }

        $this->assignVariablesToModel($data);
    }

    public function getByClause(array $params): void
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ';
        $index = 0;
        foreach ($params as $param => $value) {
            if ($index > 0) {
                $sql .= ' AND ';
            }
            $sql .= '`' . $param . '` = :' . $param;

            $index++;
        }

        $data = $this->db->fetchAssociative($sql, $params);

        if (!$data) {
            throw new NotFoundException('Unable to load site');
        }

        $this->assignVariablesToModel($data);
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $vars = get_object_vars($this->model);

        $buffer = [];

        $validColumns = $this->getValidTableColumns($this->getTableName());

        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns)) {
                    continue;
                }

                $getter = $this->snakeToCamelCase("get_" . $k);

                if (!is_callable([$this->model, $getter])) {
                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int)$value;
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update($this->getTableName(), $buffer, ["id" => $this->model->getId()]);
            return;
        }

        $this->db->insert($this->getTableName(), $buffer);
        $this->model->setId($this->db->lastInsertId());
    }

    /**
     * @throws Exception
     */
    public function delete(): void
    {
        $this->db->delete($this->getTableName(), ["id" => $this->model->getId()]);
    }

    protected function snakeToCamelCase($string): string
    {
        $str = str_replace('_', '', ucwords($string, '_'));

        return lcfirst($str);
    }
}
