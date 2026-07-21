<?php
declare(strict_types=1);

namespace API\Controller;

use API\Controller\AppController;

/**
 * Endpoints Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class EndpointsController extends AppController
{
    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authorization.Authorization');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Endpoints->find();
        $query = $this->Authorization->applyScope($query);
        $endpoints = $this->paginate($query);

        $this->set(compact('endpoints'));
    }

    /**
     * View method
     *
     * @param string|null $id Endpoint id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $endpoint = $this->Endpoints->get($id, contain: []);
        $this->Authorization->authorize($endpoint);
        $this->set(compact('endpoint'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $endpoint = $this->Endpoints->newEmptyEntity();
        $this->Authorization->authorize($endpoint);
        if ($this->request->is('post')) {
            $endpoint = $this->Endpoints->patchEntity($endpoint, $this->request->getData());
            if ($this->Endpoints->save($endpoint)) {
                $this->Flash->success(__d('API', 'The endpoint has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('API', 'The endpoint could not be saved. Please, try again.'));
        }
        $this->set(compact('endpoint'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Endpoint id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $endpoint = $this->Endpoints->get($id, contain: []);
        $this->Authorization->authorize($endpoint);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $endpoint = $this->Endpoints->patchEntity($endpoint, $this->request->getData());
            if ($this->Endpoints->save($endpoint)) {
                $this->Flash->success(__d('API', 'The endpoint has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('API', 'The endpoint could not be saved. Please, try again.'));
        }
        $this->set(compact('endpoint'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Endpoint id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $endpoint = $this->Endpoints->get($id);
        $this->Authorization->authorize($endpoint);
        if ($this->Endpoints->delete($endpoint)) {
            $this->Flash->success(__d('API', 'The endpoint has been deleted.'));
        } else {
            $this->Flash->error(__d('API', 'The endpoint could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
