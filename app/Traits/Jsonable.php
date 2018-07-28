<?php

namespace App\Traits;

trait Jsonable
{
    public function jsonData($key = null)
    {
        $jsonData = json_decode($this->json, true);
        if (empty($jsonData)) {
            $jsonData = [];
        }
        if (!empty($key)) {
            if (array_key_exists($key, $jsonData)) {
                return $jsonData[$key];
            }
            return null;
        }
        return $jsonData;
    }

    public function setJsonData($key, $value)
    {
        $data           = $this->jsonData();
        $data[$key] = $value;
        $this->json     = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->save();
    }
}
