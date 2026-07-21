<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ScheduledEmails Controller
 *
 * @property \App\Model\Table\ScheduledEmailsTable $ScheduledEmails
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class ScheduledEmailsController extends AppController
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
        $query = $this->ScheduledEmails->find()
            ->contain(['EmailTemplates', 'EmailSignatures']);
        $query = $this->Authorization->applyScope($query);
        $scheduledEmails = $this->paginate($query);

        $this->set(compact('scheduledEmails'));
    }

    /**
     * View method
     *
     * @param string|null $id Scheduled Email id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $scheduledEmail = $this->ScheduledEmails->get($id, contain: ['EmailTemplates', 'EmailSignatures']);
        $this->Authorization->authorize($scheduledEmail);
        $this->set(compact('scheduledEmail'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $scheduledEmail = $this->ScheduledEmails->newEmptyEntity();
        $this->Authorization->authorize($scheduledEmail);
        if ($this->request->is('post')) {
            $scheduledEmail = $this->ScheduledEmails->patchEntity($scheduledEmail, $this->request->getData());
            if ($this->ScheduledEmails->save($scheduledEmail)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The scheduled email has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The scheduled email could not be saved. Please, try again.'));
        }
        $emailTemplates = $this->ScheduledEmails->EmailTemplates->find('list', limit: 200)->all();
        $emailSignatures = $this->ScheduledEmails->EmailSignatures->find('list', limit: 200)->all();
        $this->set(compact('scheduledEmail', 'emailTemplates', 'emailSignatures'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Scheduled Email id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $scheduledEmail = $this->ScheduledEmails->get($id, contain: []);
        $this->Authorization->authorize($scheduledEmail);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $scheduledEmail = $this->ScheduledEmails->patchEntity($scheduledEmail, $this->request->getData());
            if ($this->ScheduledEmails->save($scheduledEmail)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The scheduled email has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The scheduled email could not be saved. Please, try again.'));
        }
        $emailTemplates = $this->ScheduledEmails->EmailTemplates->find('list', limit: 200)->all();
        $emailSignatures = $this->ScheduledEmails->EmailSignatures->find('list', limit: 200)->all();
        $this->set(compact('scheduledEmail', 'emailTemplates', 'emailSignatures'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Scheduled Email id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $scheduledEmail = $this->ScheduledEmails->get($id);
        $this->Authorization->authorize($scheduledEmail);
        if ($this->ScheduledEmails->delete($scheduledEmail)) {
            $this->Notification->success(__('The scheduled email has been deleted.'));
        } else {
            $this->Notification->error(__('The scheduled email could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
