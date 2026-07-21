<?php
declare(strict_types=1);

namespace Media\Controller;

use Media\Controller\AppController;

/**
 * Attachments Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class AttachmentsController extends AppController
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
        $query = $this->Attachments->find();
        $query = $this->Authorization->applyScope($query);
        $attachments = $this->paginate($query);

        $this->set(compact('attachments'));
    }

    /**
     * View method
     *
     * @param string|null $id Attachment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attachment = $this->Attachments->get($id, contain: []);
        $this->Authorization->authorize($attachment);
        $this->set(compact('attachment'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attachment = $this->Attachments->newEmptyEntity();
        $this->Authorization->authorize($attachment);
        if ($this->request->is('post')) {
            $attachment = $this->Attachments->patchEntity($attachment, $this->request->getData());
            if ($this->Attachments->save($attachment)) {
                $this->Flash->success(__d('Media', 'The attachment has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Media', 'The attachment could not be saved. Please, try again.'));
        }
        $this->set(compact('attachment'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Attachment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attachment = $this->Attachments->get($id, contain: []);
        $this->Authorization->authorize($attachment);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attachment = $this->Attachments->patchEntity($attachment, $this->request->getData());
            if ($this->Attachments->save($attachment)) {
                $this->Flash->success(__d('Media', 'The attachment has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Media', 'The attachment could not be saved. Please, try again.'));
        }
        $this->set(compact('attachment'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Attachment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attachment = $this->Attachments->get($id);
        $this->Authorization->authorize($attachment);
        if ($this->Attachments->delete($attachment)) {
            $this->Flash->success(__d('Media', 'The attachment has been deleted.'));
        } else {
            $this->Flash->error(__d('Media', 'The attachment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
