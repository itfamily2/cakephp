<?php
declare(strict_types=1);

namespace Notification\Controller;

use Notification\Controller\AppController;

/**
 * Alerts Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class AlertsController extends AppController
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
        $query = $this->Alerts->find();
        $query = $this->Authorization->applyScope($query);
        $alerts = $this->paginate($query);

        $this->set(compact('alerts'));
    }

    /**
     * View method
     *
     * @param string|null $id Alert id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $alert = $this->Alerts->get($id, contain: []);
        $this->Authorization->authorize($alert);
        $this->set(compact('alert'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $alert = $this->Alerts->newEmptyEntity();
        $this->Authorization->authorize($alert);
        if ($this->request->is('post')) {
            $alert = $this->Alerts->patchEntity($alert, $this->request->getData());
            if ($this->Alerts->save($alert)) {
                $this->Flash->success(__d('Notification', 'The alert has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Notification', 'The alert could not be saved. Please, try again.'));
        }
        $this->set(compact('alert'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Alert id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $alert = $this->Alerts->get($id, contain: []);
        $this->Authorization->authorize($alert);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $alert = $this->Alerts->patchEntity($alert, $this->request->getData());
            if ($this->Alerts->save($alert)) {
                $this->Flash->success(__d('Notification', 'The alert has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Notification', 'The alert could not be saved. Please, try again.'));
        }
        $this->set(compact('alert'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Alert id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $alert = $this->Alerts->get($id);
        $this->Authorization->authorize($alert);
        if ($this->Alerts->delete($alert)) {
            $this->Flash->success(__d('Notification', 'The alert has been deleted.'));
        } else {
            $this->Flash->error(__d('Notification', 'The alert could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
