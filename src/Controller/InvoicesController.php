<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Invoices Controller
 *
 * @property \App\Model\Table\InvoicesTable $Invoices
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 */
class InvoicesController extends AppController
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
        $query = $this->Invoices->find()
            ->contain(['Orders']);
        $query = $this->Authorization->applyScope($query);
        $invoices = $this->paginate($query);

        $this->set(compact('invoices'));
    }

    /**
     * View method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $invoice = $this->Invoices->get($id, contain: [
            'Orders' => [
                'Users',
                'OrderItems' => ['Products']
            ]
        ]);
        $this->Authorization->authorize($invoice);
        
        if ($this->request->getParam('_ext') === 'pdf') {
            $this->viewBuilder()->setClassName('CakePdf.Pdf');
            $this->viewBuilder()->setOption('pdfConfig', [
                'orientation' => 'portrait',
                'download' => true,
                'filename' => $invoice->invoice_number . '.pdf'
            ]);
        }
        
        $this->set(compact('invoice'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(\App\Service\InvoiceService $invoiceService)
    {
        $invoice = $this->Invoices->newEmptyEntity();
        $this->Authorization->authorize($invoice);
        
        if ($this->request->is('post')) {
            $orderId = (int)$this->request->getData('order_id');
            $notes = $this->request->getData('notes');
            
            try {
                $generatedInvoice = $invoiceService->generateFromOrder($orderId, $notes);
                
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    $this->set([
                        'success' => true,
                        'message' => 'Invoice generated successfully.',
                        'invoice' => $generatedInvoice
                    ]);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'message', 'invoice']);
                    return;
                }

                $this->Notification->success(__('The invoice has been generated.'));
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    $this->set([
                        'success' => false,
                        'message' => 'Invoice generation failed: ' . $e->getMessage()
                    ]);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                    return;
                }
                $this->Notification->error(__('Invoice failed: ') . $e->getMessage());
            }
        }
        
        // Only show orders that can be invoiced
        $orders = $this->Invoices->Orders->find('list', limit: 200)
            ->where(['status NOT IN' => ['Draft', 'Cancelled', 'Rejected']])
            ->all();
            
        $this->set(compact('invoice', 'orders'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $invoice = $this->Invoices->get($id, contain: []);
        $this->Authorization->authorize($invoice);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $invoice = $this->Invoices->patchEntity($invoice, $this->request->getData());
            if ($this->Invoices->save($invoice)) {
                $this->Notification->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The invoice could not be saved. Please, try again.'));
        }
        $orders = $this->Invoices->Orders->find('list', limit: 200)->all();
        $this->set(compact('invoice', 'orders'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $invoice = $this->Invoices->get($id);
        $this->Authorization->authorize($invoice);
        if ($this->Invoices->delete($invoice)) {
            $this->Notification->success(__('The invoice has been deleted.'));
        } else {
            $this->Notification->error(__('The invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
