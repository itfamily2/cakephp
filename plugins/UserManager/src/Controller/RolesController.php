<?php
declare(strict_types=1);

namespace UserManager\Controller;




/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class RolesController extends AppController
{
    protected ?string $defaultTable = 'Roles';
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
        $query = $this->Roles->find()->contain(['Permissions', 'UserRoles']);
        $query = $this->Authorization->applyScope($query);

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'Roles.name LIKE' => '%' . $search . '%',
            ]);
        }

        $roles = $this->paginate($query, [
            'limit' => 10,
        ]);

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('roles', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $role = $this->Roles->get($id, contain: ['Permissions', 'UserRoles']);
        $this->Authorization->authorize($role);
        $this->set(compact('role'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Roles->newEmptyEntity();
        $this->Authorization->authorize($role);
        if ($this->request->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->request->getData());
            if ($this->Roles->save($role)) {
                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The role has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The role could not be saved. Please, try again.'));
        }
        $this->set(compact('role'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $role = $this->Roles->get($id, contain: ['Permissions']);
        $this->Authorization->authorize($role);
        
        // Define system controllers for multi-select
        $controllersList = [
            'Users' => 'Users Management',
            'Roles' => 'Roles Management',
            'Groups' => 'Groups Management',
            'Permissions' => 'Permissions Management',
            'Products' => 'Products Management',
            'Categories' => 'Categories',
            'Brands' => 'Brands',
            'Orders' => 'Orders',
            'Payments' => 'Payments',
            'Invoices' => 'Invoices',
            'Settings' => 'Settings',
            'Logs' => 'System Logs',
            'Reports' => 'Reports',
            'Dashboard' => 'Dashboard',
            'EmailTemplates' => 'Email Templates',
            'EmailSignatures' => 'Email Signatures',
            'ScheduledEmails' => 'Scheduled Emails',
            'SentEmails' => 'Sent Emails',
            'ContactEnquiries' => 'Contact Enquiries'
        ];

        // Extract currently allowed controllers
        $currentControllers = [];
        foreach ($role->permissions as $perm) {
            if ($perm->allowed && $perm->action === '*') {
                $currentControllers[] = $perm->controller;
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $role = $this->Roles->patchEntity($role, $data);
            
            if ($this->Roles->save($role)) {
                
                // Handle permissions update if selected_controllers is provided
                if (isset($data['selected_controllers']) && is_array($data['selected_controllers'])) {
                    $permissionsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Permissions');
                    
                    // Delete existing broad controller permissions for this role
                    $permissionsTable->deleteAll(['role_id' => $role->id, 'action' => '*']);
                    
                    // Add new ones
                    $newPerms = [];
                    foreach ($data['selected_controllers'] as $ctrl) {
                        $newPerms[] = $permissionsTable->newEntity([
                            'role_id' => $role->id,
                            'controller' => $ctrl,
                            'action' => '*',
                            'allowed' => true
                        ]);
                    }
                    if (!empty($newPerms)) {
                        $permissionsTable->saveMany($newPerms);
                    }
                }

                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The role has been saved.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The role could not be saved. Please, try again.'));
        }
        $this->set(compact('role', 'controllersList', 'currentControllers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);
        $this->Authorization->authorize($role);
        if ($this->Roles->delete($role)) {
            $this->Notification->success(__('The role has been deleted.'));
        } else {
            $this->Notification->error(__('The role could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
