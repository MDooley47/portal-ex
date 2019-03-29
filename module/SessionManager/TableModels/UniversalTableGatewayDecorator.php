<?php

namespace SessionManager\TableModels;

use Traits\Tables\UniversalTableGatewayInterface;
use Zend\Db\TableGateway\AbstractTableGateway;

class UniversalTableGatewayDecorator implements UniversalTableGatewayInterface
{
    protected $instance;

    public function add($data)
    {
        if (method_exists($this->instance, 'add')) {
            return $this->instance->add($data);
        } else {
            return $this->{'add'.$this->model_name}($data);
        }
    }

    public function get($id)
    {
        if (method_exists($this->instance, 'get')) {
            return $this->instance->get($id);
        } else {
            return $this->{'get'.$this->model_name}($id);
        }
    }

    public function exists($id)
    {
        if (method_exists($this->instance, 'exists')) {
            return $this->instance->exists($id);
        } else {
            return $this->{lcfirst($this->model_name).'Exists'}($id);
        }
    }

    public function all()
    {
        if (method_exists($this->instance, 'all')) {
            return $this->instance->all();
        } else {
            return $this->fetchAll();
        }
    }

    public function save($model)
    {
        if (method_exists($this->instance, 'save')) {
            return $this->instance->save();
        } else {
            return $this->{'save'.$this->model_name}($model);
        }
    }

    public function delete($id)
    {
        if (method_exists($this->instance, 'delete')) {
            return $this->instance->delete($id);
        } else {
            return $this->{'delete'.$this->model_name}($id);
        }
    }

    public function __construct(AbstractTableGateway $gateway)
    {
        $this->instance = $gateway;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->instance, $method], $args);
    }

    public function __get($key)
    {
        return $this->instance->{$key};
    }

    public function __set($key, $value)
    {
        return $this->instance->{$key} = $value;
    }
}
