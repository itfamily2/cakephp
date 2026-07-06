<?php
declare(strict_types=1);

namespace CMS\Controller;

use CMS\Controller\AppController;

/**
 * Pages Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class PagesController extends AppController
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
        $query = $this->Pages->find();
        $query = $this->Authorization->applyScope($query);
        $pages = $this->paginate($query);

        $this->set(compact('pages'));
    }

    /**
     * View method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $page = $this->Pages->get($id, contain: []);
        $this->Authorization->authorize($page);
        $this->set(compact('page'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $page = $this->Pages->newEmptyEntity();
        $this->Authorization->authorize($page);
        if ($this->request->is('post')) {
            $page = $this->Pages->patchEntity($page, $this->request->getData());
            if ($this->Pages->save($page)) {
                $this->Flash->success(__d('CMS', 'The page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('CMS', 'The page could not be saved. Please, try again.'));
        }
        $this->set(compact('page'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $page = $this->Pages->get($id, contain: []);
        $this->Authorization->authorize($page);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $page = $this->Pages->patchEntity($page, $this->request->getData());
            if ($this->Pages->save($page)) {
                $this->Flash->success(__d('CMS', 'The page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('CMS', 'The page could not be saved. Please, try again.'));
        }
        $this->set(compact('page'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        $this->Authorization->authorize($page);
        if ($this->Pages->delete($page)) {
            $this->Flash->success(__d('CMS', 'The page has been deleted.'));
        } else {
            $this->Flash->error(__d('CMS', 'The page could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
