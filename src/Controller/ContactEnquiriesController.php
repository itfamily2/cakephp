<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ContactEnquiries Controller
 *
 * @property \App\Model\Table\ContactEnquiriesTable $ContactEnquiries
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class ContactEnquiriesController extends AppController
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
        $query = $this->ContactEnquiries->find()
            ->contain(['AssignedStaffs']);
        $query = $this->Authorization->applyScope($query);

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'ContactEnquiries.name LIKE' => '%' . $search . '%',
                    'ContactEnquiries.email LIKE' => '%' . $search . '%',
                    'ContactEnquiries.subject LIKE' => '%' . $search . '%',
                    'ContactEnquiries.message LIKE' => '%' . $search . '%',
                ]
            ]);
        }

        $contactEnquiries = $this->paginate($query, [
            'limit' => 10,
            'order' => ['ContactEnquiries.created' => 'DESC']
        ]);

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('contactEnquiries', 'search'));
    }

    /**
     * View method
     *
     * @param string|null $id Contact Enquiry id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contactEnquiry = $this->ContactEnquiries->get($id, contain: ['AssignedStaffs']);
        $this->Authorization->authorize($contactEnquiry);
        $this->set(compact('contactEnquiry'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contactEnquiry = $this->ContactEnquiries->newEmptyEntity();
        $this->Authorization->authorize($contactEnquiry);
        if ($this->request->is('post')) {
            $contactEnquiry = $this->ContactEnquiries->patchEntity($contactEnquiry, $this->request->getData());
            if ($this->ContactEnquiries->save($contactEnquiry)) {
                $this->Flash->success(__('The contact enquiry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contact enquiry could not be saved. Please, try again.'));
        }
        $assignedStaffs = $this->ContactEnquiries->AssignedStaffs->find('list', limit: 200)->all();
        $this->set(compact('contactEnquiry', 'assignedStaffs'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Contact Enquiry id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contactEnquiry = $this->ContactEnquiries->get($id, contain: []);
        $this->Authorization->authorize($contactEnquiry);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contactEnquiry = $this->ContactEnquiries->patchEntity($contactEnquiry, $this->request->getData());
            if ($this->ContactEnquiries->save($contactEnquiry)) {
                $this->Flash->success(__('The contact enquiry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contact enquiry could not be saved. Please, try again.'));
        }
        $assignedStaffs = $this->ContactEnquiries->AssignedStaffs->find('list', limit: 200)->all();
        $this->set(compact('contactEnquiry', 'assignedStaffs'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Contact Enquiry id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contactEnquiry = $this->ContactEnquiries->get($id);
        $this->Authorization->authorize($contactEnquiry);
        if ($this->ContactEnquiries->delete($contactEnquiry)) {
            $this->Flash->success(__('The contact enquiry has been deleted.'));
        } else {
            $this->Flash->error(__('The contact enquiry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
