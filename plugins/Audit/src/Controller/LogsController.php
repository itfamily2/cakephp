<?php
declare(strict_types=1);

namespace Audit\Controller;

use Audit\Controller\AppController;

/**
 * Logs Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class LogsController extends AppController
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
        $query = $this->Logs->find();
        $query = $this->Authorization->applyScope($query);
        $logs = $this->paginate($query);

        $this->set(compact('logs'));
    }

    /**
     * View method
     *
     * @param string|null $id Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $log = $this->Logs->get($id, contain: []);
        $this->Authorization->authorize($log);
        $this->set(compact('log'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $log = $this->Logs->newEmptyEntity();
        $this->Authorization->authorize($log);
        if ($this->request->is('post')) {
            $log = $this->Logs->patchEntity($log, $this->request->getData());
            if ($this->Logs->save($log)) {
                $this->Flash->success(__d('Audit', 'The log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Audit', 'The log could not be saved. Please, try again.'));
        }
        $this->set(compact('log'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $log = $this->Logs->get($id, contain: []);
        $this->Authorization->authorize($log);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $log = $this->Logs->patchEntity($log, $this->request->getData());
            if ($this->Logs->save($log)) {
                $this->Flash->success(__d('Audit', 'The log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Audit', 'The log could not be saved. Please, try again.'));
        }
        $this->set(compact('log'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $log = $this->Logs->get($id);
        $this->Authorization->authorize($log);
        if ($this->Logs->delete($log)) {
            $this->Flash->success(__d('Audit', 'The log has been deleted.'));
        } else {
            $this->Flash->error(__d('Audit', 'The log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
