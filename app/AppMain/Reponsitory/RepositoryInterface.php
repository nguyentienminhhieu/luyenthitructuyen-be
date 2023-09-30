<?php

namespace App\AppMain\Reponsitory;

interface RepositoryInterface
{
     /**
     * get all of row
     * @return mixed
     */
    public function all();
    /**
     * @param array $condition
     *      [
     *          [column,operator,value],
     *      ]
     * operator is consisted of '>' ,'>=', '=', '<>', '<' , '<=' ,'like','not like','in','not in'
     * example:
     *      [
     *          ["column1","=",123],
     *          ["column1","in",[1,2,4]],
     *      ]
     *
     * @param array $columns
     *
     * list of columns will be got
     * ex : ['column1','column2']
     *
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     *
     *      ["column1" => "asc"]
     * @return mixed
     */
    public function  findWhere( array $condition=[], array $columns=['*'],int $limit= 20,int $offset= 0,array $orderBy=[]);

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     *   list of columns will be got
     *   ex : ['column1','column2']
     * @return mixed
     */
    public function findOne( $attribute,$value,array $columns =['*']);

    /**
     * @return int
     */
    public function count();

    /**
     * @param array $condition
     *      [
     *          [column,operator,value],
     *      ]
     * operator is consisted of '>' ,'>=', '=', '<>', '<' , '<=' ,'like','not like','in','not in'
     *  example:
     *      [
     *          ["column1","=",123],
     *          ["column1","in",[1,2,4]],
     *      ]
     *
     * @return mixed
     */
    public function countWhere(array $condition=[]);

    /**
     * Get one by primary key
     * @param $id
     * @param array $columns
     *   list of columns will be got
     *   ex : ['column1','column2']
     * @return mixed
     */
    public function find($id,array $columns =['*']);

    /**
     * Create
     * @param array $data
     *  ex :
     *
     * insert one row
     * [
     *      "column1" => 1,
     *      "column2" => "abc"
     * ]
     *
     * or insert multi row
     * [
     *      [
     *          "column1" => 1,
     *          "column2" => "abc",
     *      ],
     *      [
     *          "column1" => 2,
     *          "column2" => "abcd",
     *      ],
     *  ]
     *
     * @return mixed
     */
    public function insert(array $data);


    /**
     * Update
     * @param $attribute
     * @param $value
     * @param array $data
     *  ex :
     * array (
     *      "column1" => 1,
     *      "column2" => "abc"
     * )
     * @return mixed
     */
    public function update($attribute,$value, array $data);

    /**
     * @param array $condition
     *      [
     *          [column,operator,value],
     *      ]
     * operator is consisted of '>' ,'>=', '=', '<>', '<' , '<=' ,'like','not like','in','not in'
     *  example:
     *      [
     *          ["column1","=",123],
     *          ["column1","in",[1,2,4]],
     *      ]
     *
     * @param array $data
     *  ex :
     * array (
     *      "column1" => 1,
     *      "column2" => "abc"
     * )
     * @return int
     */
    public function updateOrCreate($id, array $data);
    public function updateWhere(array $condition=[], array $data);

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 1, array $columns = ['*']);

    /**
     * insert one row to db
     * @param array $data
     *      [
     *          "column1" => 1,
     *          "column2" => "abc",
     *      ]
     * @return mixed
     */
    public function create(array $data);

    /**
     * Delete
     * @param $id
     * id is value of primary key
     * @return mixed
     */
    public function createMulti(array $data);

    /**
     * Delete
     * @param $id
     * id is value of primary key
     * @return mixed
     */
    public function delete($id);

    /**
     * @param array $condition
     *      [
     *          [column,operator,value],
     *      ]
     * operator is consisted of '>' ,'>=', '=', '<>', '<' , '<=' ,'like','not like','in','not in'
     *  example:
     *      [
     *          ["column1","=",123],
     *          ["column1","in",[1,2,4]],
     *      ]
     *
     * @return mixed
     */
    public function deleteWhere($attribute, $value);

    /**
     * Get one by primary key
     * @param $id
     * @param array $columns
     *   list of columns will be got
     *   ex : ['column1','column2']
     * @return mixed
     */
    public function findOrFail($id,array $columns =['*']);
}

