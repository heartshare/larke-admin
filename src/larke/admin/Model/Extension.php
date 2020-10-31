<?php

namespace Larke\Admin\Model;

use Illuminate\Support\Facades\Cache;

/*
 * Extension
 *
 * @create 2020-10-30
 * @author deatil
 */
class Extension extends Base
{
    protected $table = 'larke_extension';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    
    protected $guarded = [];
    
    protected $appends = [
        'config_arr',
        'config_data_arr',
    ];
    
    public $incrementing = false;
    public $timestamps = false;
    
    public static function getExtensions()
    {
        return Cache::rememberForever(md5('larke.model.extensions'), function() {
            return self::all()->mapWithKeys(function ($extension) {
                return [$extension->name => $extension->toArray()];
            })->toArray();
        });
    }
    
    public function clearCahce()
    {
        Cache::forget(md5('larke.model.extensions'));
    }
    
    public function getConfigArrAttribute() 
    {
        $config = $this->config;
        if (empty($config)) {
            return [];
        }
        
        return json_decode($config);
    }
    
    public function getConfigDataArrAttribute() 
    {
        $config_data = $this->config_data;
        if (empty($config_data)) {
            return [];
        }
        
        return json_decode($config_data);
    }
    
    public function enable() 
    {
        return $this->update([
            'status' => 1,
        ]);
    }
    
    public function disable() 
    {
        return $this->update([
            'status' => 0,
        ]);
    }
    
}