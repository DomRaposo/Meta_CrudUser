<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\Connector;

class MongoDBServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registra o driver MongoDB personalizado
        DB::extend('mongodb', function ($config, $name) {
            $config['database'] = $config['database'] ?? 'to_do';
            
            return new class($config) extends Connection {
                private $config;
                private $baseUrl;
                
                public function __construct($config)
                {
                    $this->config = $config;
                    $this->baseUrl = "http://{$config['host']}:{$config['port']}/{$config['database']}";
                    parent::__construct(new Connector(), $config, $name);
                }
                
                public function collection($name)
                {
                    return new class($this->baseUrl, $name) {
                        private $baseUrl;
                        private $collection;
                        
                        public function __construct($baseUrl, $collection)
                        {
                            $this->baseUrl = $baseUrl;
                            $this->collection = $collection;
                        }
                        
                        public function insert($data)
                        {
                            $url = "{$this->baseUrl}/{$this->collection}";
                            $response = $this->makeRequest('POST', $url, $data);
                            return $response;
                        }
                        
                        public function find($id = null)
                        {
                            $url = "{$this->baseUrl}/{$this->collection}";
                            if ($id) {
                                $url .= "/{$id}";
                            }
                            return $this->makeRequest('GET', $url);
                        }
                        
                        public function update($id, $data)
                        {
                            $url = "{$this->baseUrl}/{$this->collection}/{$id}";
                            return $this->makeRequest('PUT', $url, $data);
                        }
                        
                        public function delete($id)
                        {
                            $url = "{$this->baseUrl}/{$this->collection}/{$id}";
                            return $this->makeRequest('DELETE', $url);
                        }
                        
                        private function makeRequest($method, $url, $data = null)
                        {
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                            
                            if ($data && in_array($method, ['POST', 'PUT'])) {
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Content-Length: ' . strlen(json_encode($data))
                                ]);
                            }
                            
                            $response = curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            
                            if ($httpCode >= 200 && $httpCode < 300) {
                                return json_decode($response, true);
                            }
                            
                            return null;
                        }
                    };
                }
                
                public function getPdo()
                {
                    // Retorna um PDO mock para compatibilidade
                    return new \PDO('sqlite::memory:');
                }
                
                public function getReadPdo()
                {
                    return $this->getPdo();
                }
            };
        });
    }

    public function boot()
    {
        //
    }
} 