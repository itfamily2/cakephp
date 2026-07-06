<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ActivityLogs Controller
 *
 * @property \App\Model\Table\ActivityLogsTable $ActivityLogs
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class ActivityLogsController extends AppController
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
        $query = $this->ActivityLogs->find()
            ->contain(['Users']);
        $query = $this->Authorization->applyScope($query);

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'ActivityLogs.action LIKE' => '%' . $search . '%',
                    'ActivityLogs.description LIKE' => '%' . $search . '%',
                    'ActivityLogs.ip_address LIKE' => '%' . $search . '%',
                    'Users.username LIKE' => '%' . $search . '%',
                ]
            ]);
        }

        $activityLogs = $this->paginate($query, [
            'limit' => 15,
            'order' => ['ActivityLogs.created' => 'DESC']
        ]);

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('activityLogs', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Activity Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $activityLog = $this->ActivityLogs->get($id, contain: ['Users']);
        $this->Authorization->authorize($activityLog);
        $this->set(compact('activityLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $activityLog = $this->ActivityLogs->newEmptyEntity();
        $this->Authorization->authorize($activityLog);
        if ($this->request->is('post')) {
            $activityLog = $this->ActivityLogs->patchEntity($activityLog, $this->request->getData());
            if ($this->ActivityLogs->save($activityLog)) {
                $this->Notification->success(__('The activity log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The activity log could not be saved. Please, try again.'));
        }
        $users = $this->ActivityLogs->Users->find('list', limit: 200)->all();
        $this->set(compact('activityLog', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Activity Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $activityLog = $this->ActivityLogs->get($id, contain: []);
        $this->Authorization->authorize($activityLog);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $activityLog = $this->ActivityLogs->patchEntity($activityLog, $this->request->getData());
            if ($this->ActivityLogs->save($activityLog)) {
                $this->Notification->success(__('The activity log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The activity log could not be saved. Please, try again.'));
        }
        $users = $this->ActivityLogs->Users->find('list', limit: 200)->all();
        $this->set(compact('activityLog', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Activity Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $activityLog = $this->ActivityLogs->get($id);
        $this->Authorization->authorize($activityLog);
        if ($this->ActivityLogs->delete($activityLog)) {
            $this->Notification->success(__('The activity log has been deleted.'));
        } else {
            $this->Notification->error(__('The activity log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
