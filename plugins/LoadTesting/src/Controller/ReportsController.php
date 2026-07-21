<?php
declare(strict_types=1);

namespace LoadTesting\Controller;

use LoadTesting\Controller\AppController;

class ReportsController extends AppController
{
    protected ?string $defaultTable = null;

    public function index()
    {
        if ($this->components()->has('Authorization')) {
            $this->Authorization->skipAuthorization();
        }
    }
}