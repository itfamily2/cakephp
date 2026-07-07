<?php
declare(strict_types=1);

namespace UserManager\Controller;




/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class GroupsController extends AppController
{
    protected ?string $defaultTable = 'Groups';
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
        $query = $this->Groups->find()
            ->contain(['ParentGroups', 'GroupUsers', 'Permissions']);
        $query = $this->Authorization->applyScope($query);

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'Groups.name LIKE' => '%' . $search . '%',
            ]);
        }

        $groups = $this->paginate($query, [
            'limit' => 10,
        ]);

        $this->set(compact('groups', 'search'));

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }
    }

    /**
     * View method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $group = $this->Groups->get($id, contain: ['ParentGroups', 'GroupUsers', 'ChildGroups', 'Permissions']);
        $this->Authorization->authorize($group);
        $this->set(compact('group'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $group = $this->Groups->newEmptyEntity();
        $this->Authorization->authorize($group);
        if ($this->request->is('post')) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            if ($this->Groups->save($group)) {
                $this->Notification->success(__('The group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The group could not be saved. Please, try again.'));
        }
        $parentGroups = $this->Groups->ParentGroups->find('list', limit: 200)->all();
        $this->set(compact('group', 'parentGroups'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $group = $this->Groups->get($id, contain: []);
        $this->Authorization->authorize($group);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            if ($this->Groups->save($group)) {
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => true,
                            'message' => __('The group has been saved.'),
                            'redirect' => $this->Url->build(['action' => 'index'])
                        ]));
                }
                $this->Notification->success(__('The group has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')->withStatus(400)
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => __('The group could not be saved. Please, try again.'),
                        'errors' => $group->getErrors()
                    ]));
            }
            $this->Notification->error(__('The group could not be saved. Please, try again.'));
        }
        $parentGroups = $this->Groups->ParentGroups->find('list', limit: 200)->all();
        $this->set(compact('group', 'parentGroups'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $group = $this->Groups->get($id);
        $this->Authorization->authorize($group);
        if ($this->Groups->delete($group)) {
            $this->Notification->success(__('The group has been deleted.'));
        } else {
            $this->Notification->error(__('The group could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
