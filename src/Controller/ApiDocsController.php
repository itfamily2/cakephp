<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ApiDocs Controller
 * 
 * Serves the Swagger UI for testing the REST API.
 */
class ApiDocsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Allow public access to view the API documentation
        $this->Authorization->skipAuthorization();
        $this->Authentication->addUnauthenticatedActions(['index']);
    }

    public function index()
    {
        // Render a view that loads Swagger UI from a CDN
        $this->viewBuilder()->setLayout('ajax');
    }
}
