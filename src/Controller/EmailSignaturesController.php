<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * EmailSignatures Controller
 *
 * @property \App\Model\Table\EmailSignaturesTable $EmailSignatures
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class EmailSignaturesController extends AppController
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
        $query = $this->EmailSignatures->find()
            ->contain(['Users']);
        $query = $this->Authorization->applyScope($query);
        $emailSignatures = $this->paginate($query);

        $this->set(compact('emailSignatures'));
    }

    /**
     * View method
     *
     * @param string|null $id Email Signature id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $emailSignature = $this->EmailSignatures->get($id, contain: ['Users', 'ScheduledEmails', 'SentEmails']);
        $this->Authorization->authorize($emailSignature);
        $this->set(compact('emailSignature'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $emailSignature = $this->EmailSignatures->newEmptyEntity();
        $this->Authorization->authorize($emailSignature);
        if ($this->request->is('post')) {
            $emailSignature = $this->EmailSignatures->patchEntity($emailSignature, $this->request->getData());
            if ($this->EmailSignatures->save($emailSignature)) {
                $this->Flash->success(__('The email signature has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The email signature could not be saved. Please, try again.'));
        }
        $users = $this->EmailSignatures->Users->find('list', limit: 200)->all();
        $this->set(compact('emailSignature', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Email Signature id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $emailSignature = $this->EmailSignatures->get($id, contain: []);
        $this->Authorization->authorize($emailSignature);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $emailSignature = $this->EmailSignatures->patchEntity($emailSignature, $this->request->getData());
            if ($this->EmailSignatures->save($emailSignature)) {
                $this->Flash->success(__('The email signature has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The email signature could not be saved. Please, try again.'));
        }
        $users = $this->EmailSignatures->Users->find('list', limit: 200)->all();
        $this->set(compact('emailSignature', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Email Signature id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $emailSignature = $this->EmailSignatures->get($id);
        $this->Authorization->authorize($emailSignature);
        if ($this->EmailSignatures->delete($emailSignature)) {
            $this->Flash->success(__('The email signature has been deleted.'));
        } else {
            $this->Flash->error(__('The email signature could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
