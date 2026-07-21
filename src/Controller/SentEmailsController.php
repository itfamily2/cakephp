<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * SentEmails Controller
 *
 * @property \App\Model\Table\SentEmailsTable $SentEmails
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class SentEmailsController extends AppController
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
        $query = $this->SentEmails->find()
            ->contain(['EmailTemplates', 'EmailSignatures']);
        $query = $this->Authorization->applyScope($query);
        $sentEmails = $this->paginate($query);

        $this->set(compact('sentEmails'));
    }

    /**
     * View method
     *
     * @param string|null $id Sent Email id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sentEmail = $this->SentEmails->get($id, contain: ['EmailTemplates', 'EmailSignatures']);
        $this->Authorization->authorize($sentEmail);
        $this->set(compact('sentEmail'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sentEmail = $this->SentEmails->newEmptyEntity();
        $this->Authorization->authorize($sentEmail);
        if ($this->request->is('post')) {
            $sentEmail = $this->SentEmails->patchEntity($sentEmail, $this->request->getData());
            if ($this->SentEmails->save($sentEmail)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The sent email has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The sent email could not be saved. Please, try again.'));
        }
        $emailTemplates = $this->SentEmails->EmailTemplates->find('list', limit: 200)->all();
        $emailSignatures = $this->SentEmails->EmailSignatures->find('list', limit: 200)->all();
        $this->set(compact('sentEmail', 'emailTemplates', 'emailSignatures'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sent Email id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sentEmail = $this->SentEmails->get($id, contain: []);
        $this->Authorization->authorize($sentEmail);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sentEmail = $this->SentEmails->patchEntity($sentEmail, $this->request->getData());
            if ($this->SentEmails->save($sentEmail)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The sent email has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The sent email could not be saved. Please, try again.'));
        }
        $emailTemplates = $this->SentEmails->EmailTemplates->find('list', limit: 200)->all();
        $emailSignatures = $this->SentEmails->EmailSignatures->find('list', limit: 200)->all();
        $this->set(compact('sentEmail', 'emailTemplates', 'emailSignatures'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sent Email id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sentEmail = $this->SentEmails->get($id);
        $this->Authorization->authorize($sentEmail);
        if ($this->SentEmails->delete($sentEmail)) {
            $this->Notification->success(__('The sent email has been deleted.'));
        } else {
            $this->Notification->error(__('The sent email could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
