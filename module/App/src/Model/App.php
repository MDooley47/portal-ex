<?php

namespace App\Model;

class App
{
    public $id;
    public $name;
    public $url;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->url = !empty($data['url']) ? $data['url'] : null;
        $this->iconPath = !empty($data['iconPath']) ? $data['iconPath'] : null;
    }
}
