<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

/** API v1 — Settings Controller */
class SettingsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    public function index(): void
    {
        $settings = $this->Settings->find()->all();
        $this->set('success', true);
        $this->set('data', $settings);
        $this->viewBuilder()->setOption('serialize', ['success', 'data']);
    }

    public function view(int $id): void
    {
        $this->jsonSuccess($this->Settings->get($id));
    }

    public function add(): void
    {
        $this->request->allowMethod(['post']);
        $setting = $this->Settings->patchEntity($this->Settings->newEmptyEntity(), $this->request->getData());
        if ($this->Settings->save($setting)) {
            $this->jsonSuccess($setting, 'Setting created', 201);
        } else {
            $this->jsonError('Validation failed', $setting->getErrors());
        }
    }

    public function edit(int $id): void
    {
        $this->request->allowMethod(['put', 'patch']);
        $setting = $this->Settings->patchEntity($this->Settings->get($id), $this->request->getData());
        if ($this->Settings->save($setting)) {
            $this->jsonSuccess($setting, 'Setting updated');
        } else {
            $this->jsonError('Validation failed', $setting->getErrors());
        }
    }

    public function delete(int $id): void
    {
        $this->request->allowMethod(['delete']);
        if ($this->Settings->delete($this->Settings->get($id))) {
            $this->jsonSuccess([], 'Setting deleted');
        } else {
            $this->jsonError('Could not delete setting', [], 500);
        }
    }
}
