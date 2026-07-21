<?php
declare(strict_types=1);

namespace Settings\Controller;

use Settings\Controller\AppController;

/**
 * Preferences Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class PreferencesController extends AppController
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
        $query = $this->Preferences->find();
        $query = $this->Authorization->applyScope($query);
        $preferences = $this->paginate($query);

        $this->set(compact('preferences'));
    }

    /**
     * View method
     *
     * @param string|null $id Preference id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $preference = $this->Preferences->get($id, contain: []);
        $this->Authorization->authorize($preference);
        $this->set(compact('preference'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $preference = $this->Preferences->newEmptyEntity();
        $this->Authorization->authorize($preference);
        if ($this->request->is('post')) {
            $preference = $this->Preferences->patchEntity($preference, $this->request->getData());
            if ($this->Preferences->save($preference)) {
                $this->Flash->success(__d('Settings', 'The preference has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Settings', 'The preference could not be saved. Please, try again.'));
        }
        $this->set(compact('preference'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Preference id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $preference = $this->Preferences->get($id, contain: []);
        $this->Authorization->authorize($preference);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $preference = $this->Preferences->patchEntity($preference, $this->request->getData());
            if ($this->Preferences->save($preference)) {
                $this->Flash->success(__d('Settings', 'The preference has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Settings', 'The preference could not be saved. Please, try again.'));
        }
        $this->set(compact('preference'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Preference id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $preference = $this->Preferences->get($id);
        $this->Authorization->authorize($preference);
        if ($this->Preferences->delete($preference)) {
            $this->Flash->success(__d('Settings', 'The preference has been deleted.'));
        } else {
            $this->Flash->error(__d('Settings', 'The preference could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
