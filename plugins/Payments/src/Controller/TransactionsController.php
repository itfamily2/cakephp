<?php
declare(strict_types=1);

namespace Payments\Controller;

use Payments\Controller\AppController;

/**
 * Transactions Controller
 *
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class TransactionsController extends AppController
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
        $query = $this->Transactions->find();
        $query = $this->Authorization->applyScope($query);
        $transactions = $this->paginate($query);

        $this->set(compact('transactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->get($id, contain: []);
        $this->Authorization->authorize($transaction);
        $this->set(compact('transaction'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transaction = $this->Transactions->newEmptyEntity();
        $this->Authorization->authorize($transaction);
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__d('Payments', 'The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Payments', 'The transaction could not be saved. Please, try again.'));
        }
        $this->set(compact('transaction'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transaction = $this->Transactions->get($id, contain: []);
        $this->Authorization->authorize($transaction);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__d('Payments', 'The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__d('Payments', 'The transaction could not be saved. Please, try again.'));
        }
        $this->set(compact('transaction'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        $this->Authorization->authorize($transaction);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__d('Payments', 'The transaction has been deleted.'));
        } else {
            $this->Flash->error(__d('Payments', 'The transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
