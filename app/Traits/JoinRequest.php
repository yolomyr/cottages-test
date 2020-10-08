<?php


namespace App\Traits;


trait JoinRequest
{
    public function joinModels() {
        if (!isset($this->joinable_models) || empty($this->joinable_models)) {
            return [];
        }

        $data = [];
        foreach ($this->joinable_models as $model_key => $model_class) {
            $data[$model_key] = $model_class::all();
        }

        return $data;
    }
}
