<?php


namespace Inpush\Models;

use Inpush\Traits\Paramsable;

class Model {
    use Paramsable;

    public $model;

    public function __construct(Array $params = []) {

        $this->add_params($params);

    }

}
