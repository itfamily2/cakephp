<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AuditLogs Controller
 *
 * @property \App\Model\Table\AuditLogsTable $AuditLogs
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class AuditLogsController extends AppController
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
        $query = $this->AuditLogs->find()
            ->contain(['Users']);
        $query = $this->Authorization->applyScope($query);

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'AuditLogs.action LIKE' => '%' . $search . '%',
                    'AuditLogs.module LIKE' => '%' . $search . '%',
                    'AuditLogs.ip_address LIKE' => '%' . $search . '%',
                    'Users.username LIKE' => '%' . $search . '%',
                ]
            ]);
        }

        $auditLogs = $this->paginate($query, [
            'limit' => 15,
            'order' => ['AuditLogs.created' => 'DESC']
        ]);

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('auditLogs', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Audit Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $auditLog = $this->AuditLogs->get($id, contain: ['Users']);
        $this->Authorization->authorize($auditLog);
        $this->set(compact('auditLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $auditLog = $this->AuditLogs->newEmptyEntity();
        $this->Authorization->authorize($auditLog);
        if ($this->request->is('post')) {
            $auditLog = $this->AuditLogs->patchEntity($auditLog, $this->request->getData());
            if ($this->AuditLogs->save($auditLog)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The audit log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The audit log could not be saved. Please, try again.'));
        }
        $users = $this->AuditLogs->Users->find('list', limit: 200)->all();
        $this->set(compact('auditLog', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Audit Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $auditLog = $this->AuditLogs->get($id, contain: []);
        $this->Authorization->authorize($auditLog);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $auditLog = $this->AuditLogs->patchEntity($auditLog, $this->request->getData());
            if ($this->AuditLogs->save($auditLog)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The audit log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The audit log could not be saved. Please, try again.'));
        }
        $users = $this->AuditLogs->Users->find('list', limit: 200)->all();
        $this->set(compact('auditLog', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Audit Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $auditLog = $this->AuditLogs->get($id);
        $this->Authorization->authorize($auditLog);
        if ($this->AuditLogs->delete($auditLog)) {
            $this->Notification->success(__('The audit log has been deleted.'));
        } else {
            $this->Notification->error(__('The audit log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
