<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class MongoModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->collection = $this->getTable();
    }
    
    public static function create(array $attributes = [])
    {
        $model = new static($attributes);
        $model->fill($attributes);
        
        $data = $model->getAttributes();
        $data['created_at'] = now();
        $data['updated_at'] = now();
        
        $result = DB::connection('mongodb')->table($model->collection)->insertGetId($data);
        if ($result) {
            $model->setAttribute('_id', $result);
            $model->id = (string)$result; // sempre string
            return $model;
        }
        return null;
    }
    
    public static function find($id)
    {
        $model = new static();
        // Sempre buscar por _id (MongoDB)
        $result = DB::connection('mongodb')->table($model->collection)->where('_id', $id)->first();
        if ($result) {
            $model->setRawAttributes((array)$result);
            $model->id = isset($model->attributes['_id']) ? (string)$model->attributes['_id'] : null;
            return $model;
        }
        return null;
    }
    
    public static function all($columns = ['*'])
    {
        $model = new static();
        $results = DB::connection('mongodb')->table($model->collection)->get($columns);
        $models = [];
        foreach ($results as $result) {
            $newModel = new static();
            $newModel->setRawAttributes((array)$result);
            $newModel->id = isset($newModel->attributes['_id']) ? (string)$newModel->attributes['_id'] : null;
            $models[] = $newModel;
        }
        return collect($models);
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        $this->fill($attributes);
        $data = $this->getAttributes();
        $data['updated_at'] = now();
        $result = DB::connection('mongodb')->table($this->collection)->where('_id', $this->getKey())->update($data);
        if ($result) {
            $this->setRawAttributes($data);
            return $this;
        }
        return false;
    }
    
    public function delete()
    {
        return DB::connection('mongodb')->table($this->collection)->where('_id', $this->getKey())->delete();
    }
    
    public function getKey()
    {
        // Sempre retornar _id (MongoDB)
        return $this->attributes['_id'] ?? null;
    }
    
    // Forçar o uso da conexão MongoDB
    public function getConnectionName()
    {
        return 'mongodb';
    }
} 