<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CmsPages Controller
 *
 * @property \App\Model\Table\CmsPagesTable $CmsPages
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class CmsPagesController extends AppController
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
        $query = $this->CmsPages->find();
        $query = $this->Authorization->applyScope($query);

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'CmsPages.title LIKE' => '%' . $search . '%',
                    'CmsPages.slug LIKE' => '%' . $search . '%',
                    'CmsPages.meta_title LIKE' => '%' . $search . '%',
                ]
            ]);
        }

        $cmsPages = $this->paginate($query, [
            'limit' => 10,
        ]);

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('cmsPages', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Cms Page id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cmsPage = $this->CmsPages->get($id, contain: []);
        $this->Authorization->authorize($cmsPage);
        $this->set(compact('cmsPage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cmsPage = $this->CmsPages->newEmptyEntity();
        $this->Authorization->authorize($cmsPage);
        if ($this->request->is('post')) {
            $cmsPage = $this->CmsPages->patchEntity($cmsPage, $this->request->getData());
            if ($this->CmsPages->save($cmsPage)) {
                $this->Notification->success(__('The cms page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The cms page could not be saved. Please, try again.'));
        }
        $this->set(compact('cmsPage'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Cms Page id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cmsPage = $this->CmsPages->get($id, contain: []);
        $this->Authorization->authorize($cmsPage);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cmsPage = $this->CmsPages->patchEntity($cmsPage, $this->request->getData());
            if ($this->CmsPages->save($cmsPage)) {
                $this->Notification->success(__('The cms page has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The cms page could not be saved. Please, try again.'));
        }
        $this->set(compact('cmsPage'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cms Page id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cmsPage = $this->CmsPages->get($id);
        $this->Authorization->authorize($cmsPage);
        if ($this->CmsPages->delete($cmsPage)) {
            $this->Notification->success(__('The cms page has been deleted.'));
        } else {
            $this->Notification->error(__('The cms page could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
