<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * EmailTemplates Controller
 *
 * @property \App\Model\Table\EmailTemplatesTable $EmailTemplates
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class EmailTemplatesController extends AppController
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
        $query = $this->EmailTemplates->find()
            ->contain(['Users']);
        $query = $this->Authorization->applyScope($query);
        $emailTemplates = $this->paginate($query);

        $this->set(compact('emailTemplates'));
    }

    /**
     * View method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $emailTemplate = $this->EmailTemplates->get($id, contain: ['Users', 'ScheduledEmails', 'SentEmails']);
        $this->Authorization->authorize($emailTemplate);
        $this->set(compact('emailTemplate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $emailTemplate = $this->EmailTemplates->newEmptyEntity();
        $this->Authorization->authorize($emailTemplate);
        if ($this->request->is('post')) {
            $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());
            if ($this->EmailTemplates->save($emailTemplate)) {
                $this->Notification->success(__('The email template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The email template could not be saved. Please, try again.'));
        }
        $users = $this->EmailTemplates->Users->find('list', limit: 200)->all();
        $this->set(compact('emailTemplate', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $emailTemplate = $this->EmailTemplates->get($id, contain: []);
        $this->Authorization->authorize($emailTemplate);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());
            if ($this->EmailTemplates->save($emailTemplate)) {
                $this->Notification->success(__('The email template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The email template could not be saved. Please, try again.'));
        }
        $users = $this->EmailTemplates->Users->find('list', limit: 200)->all();
        $this->set(compact('emailTemplate', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $emailTemplate = $this->EmailTemplates->get($id);
        $this->Authorization->authorize($emailTemplate);
        if ($this->EmailTemplates->delete($emailTemplate)) {
            $this->Notification->success(__('The email template has been deleted.'));
        } else {
            $this->Notification->error(__('The email template could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
