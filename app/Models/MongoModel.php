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
        
        $result = DB::connection('mongodb')->collection($model->collection)->insert($data);
        
        if ($result) {
            $model->setRawAttributes($result);
            return $model;
        }
        
        return null;
    }
    
    public static function find($id)
    {
        $model = new static();
        $result = DB::connection('mongodb')->collection($model->collection)->find($id);
        
        if ($result) {
            $model->setRawAttributes($result);
            return $model;
        }
        
        return null;
    }
    
    public static function all()
    {
        $model = new static();
        $results = DB::connection('mongodb')->collection($model->collection)->find();
        
        $models = [];
        foreach ($results as $result) {
            $newModel = new static();
            $newModel->setRawAttributes($result);
            $models[] = $newModel;
        }
        
        return collect($models);
    }
    
    public function update(array $attributes = [])
    {
        $this->fill($attributes);
        $data = $this->getAttributes();
        $data['updated_at'] = now();
        
        $result = DB::connection('mongodb')->collection($this->collection)->update($this->getKey(), $data);
        
        if ($result) {
            $this->setRawAttributes($result);
            return $this;
        }
        
        return false;
    }
    
    public function delete()
    {
        return DB::connection('mongodb')->collection($this->collection)->delete($this->getKey());
    }
    
    public function getKey()
    {
        return $this->attributes['_id'] ?? $this->attributes['id'] ?? null;
    }
} 