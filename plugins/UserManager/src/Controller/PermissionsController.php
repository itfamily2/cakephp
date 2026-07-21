<?php
declare(strict_types=1);

namespace UserManager\Controller;




use Cake\ORM\TableRegistry;

/**
 * PermissionsController — Phase 4: Full CRUD with ALL Phase 4 techniques
 *
 * This controller is the most complete learning example in the project.
 * Every action demonstrates a different Phase 4 technique with comments.
 *
 * WHAT IT MANAGES:
 *   The permissions table maps (role_id OR group_id) + controller + action → allowed.
 *   This is the database-driven ACL system powering RequestPolicy.php.
 *
 * PHASE 4 TECHNIQUES DEMONSTRATED IN THIS FILE:
 *   ✅ initialize()    → component loading, per-controller paginate config
 *   ✅ beforeFilter()  → public action whitelist, auth bypass
 *   ✅ beforeRender()  → inject shared view data for all actions
 *   ✅ paginate()      → sorting, limits, custom conditions
 *   ✅ redirect()      → post-save redirect with named routes
 *   ✅ Flash           → success/error session messages
 *   ✅ Session         → reading/writing via Authentication session
 *   ✅ Request         → getQuery(), getData(), getParam(), is()
 *   ✅ Response        → withType(), withHeader(), withStringBody()
 *   ✅ JSON response   → viewBuilder()->setClassName('Json')
 *   ✅ XML response    → setClassName('Xml')
 *   ✅ Download        → force-download binary response
 *   ✅ Upload          → file upload handling
 *   ✅ AJAX            → request->is('ajax') partial rendering
 *   ✅ Validation      → entity validation errors surfaced to view
 */
class PermissionsController extends AppController
{
    protected ?string $defaultTable = 'Permissions';
    // =========================================================================
    // 1. initialize() — Component loading and controller-wide configuration
    // =========================================================================
    // Runs ONCE per request, before beforeFilter() and before the action.
    // This is the correct place to load components. Never do it in actions.
    //
    // INTERVIEW: "initialize() is the constructor equivalent for CakePHP
    // controllers. By calling parent::initialize() first, I inherit all
    // components loaded in AppController (Flash, Auth, Authorization).
    // Then I add controller-specific configuration on top."
    // =========================================================================
    public function initialize(): void
    {
        // Must call parent first — this loads Flash, Authentication, Authorization
        parent::initialize();

        // Set controller-wide pagination defaults
        // Any paginate() call here uses these unless overridden per-call
        $this->paginate = [
            'limit'          => 20,
            'order'          => ['Permissions.controller' => 'ASC', 'Permissions.action' => 'ASC'],
            'sortableFields' => ['Permissions.controller', 'Permissions.action', 'Permissions.allowed'],
        ];
    }


    // =========================================================================
    // 2. beforeFilter() — Access control and per-request setup
    // =========================================================================
    // Runs AFTER initialize(), BEFORE the action method executes.
    // Returning $this->redirect() or setting $event->stopPropagation()
    // prevents the action from running.
    //
    // INTERVIEW: "beforeFilter() is the first HTTP-aware lifecycle hook.
    // I use it to check if the user is logged in, verify their role,
    // and abort the request early with a redirect if they don't qualify."
    // =========================================================================
    public function beforeFilter(\Cake\Event\EventInterface $event): ?\Cake\Http\Response
    {
        parent::beforeFilter($event);
        return null;
    }


    // =========================================================================
    // 3. beforeRender() — Inject shared view variables
    // =========================================================================
    // Fires after the action completes but before the template is rendered.
    // Variables set here are available in ALL templates of this controller,
    // including layout templates.
    //
    // INTERVIEW: "beforeRender() is the last point where I can inject data
    // into the view. I use it for things like sidebar counts, breadcrumb
    // data, or switching to JSON/XML format based on the Accept header."
    // =========================================================================
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);

        // Inject role and group lists needed by permission forms
        // Available as $roles and $groups in ALL permission templates
        $this->set('roles',  $this->fetchTable('Roles')->find('list')->all());
        $this->set('groups', $this->fetchTable('Groups')->find('list')->all());
    }


    // =========================================================================
    // 4. index() — paginate() + AJAX + Request::getQuery()
    // =========================================================================
    public function index(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        // Build base query
        $query = $this->fetchTable('Permissions')->find()
            ->contain(['Roles', 'Groups']);

        // --- REQUEST: getQuery() reads URL query parameters ---
        // $this->request is a ServerRequest object (PSR-7 compliant)
        // getQuery('key') → reads ?key=value from the URL
        // getData('key')  → reads POST body data
        // getParam('key') → reads route parameters (controller, action, prefix)
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'Permissions.controller LIKE' => '%' . $search . '%',
                    'Permissions.action LIKE'     => '%' . $search . '%',
                ]
            ]);
        }

        $roleFilter = $this->request->getQuery('role_id');
        if (!empty($roleFilter)) {
            $query->where(['Permissions.role_id' => $roleFilter]);
        }

        // --- PAGINATE: paginate() wraps the query with limit/page/sort ---
        // Returns a \Cake\ORM\ResultSet (iterable entity collection)
        // Injects $this->request->getParam('?') automatically for page/sort
        $permissions = $this->paginate($query);

        // --- AJAX: return partial view for dynamic table updates ---
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('permissions', 'search', 'roleFilter'));
        return null;
    }


    // =========================================================================
    // 5. add() — Validation + Flash + redirect()
    // =========================================================================
    public function add(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        $permissionsTable = $this->fetchTable('Permissions');
        $permission = $permissionsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            // --- VALIDATION: patchEntity() applies validationDefault() rules ---
            // If validation fails, errors are set on $permission->getErrors()
            // and accessible in the template via $this->Form->error()
            $permission = $permissionsTable->patchEntity(
                $permission,
                $this->request->getData() // Reads POST body
            );

            if ($permissionsTable->save($permission)) {
                // --- FLASH: session-based one-time notification ---
                // FlashComponent writes to the session.
                // $this->Flash->render() in the template reads and clears it.
                $this->Notification->success(__('Permission saved.'));

                // --- REDIRECT: sends HTTP 302 response to the browser ---
                // Using array format generates URL via the Router
                // ['action' => 'index'] → /permissions
                return $this->redirect(['action' => 'index']);
            }

            // Validation or save failure — flash error, re-render form
            $this->Notification->error(__('Could not save permission. Check the highlighted fields.'));
        }

        // Pass entity to view so the form shows validation errors
        $this->set(compact('permission'));
        return null;
    }


    // =========================================================================
    // 6. edit() — Request method checking + patchEntity() + redirect()
    // =========================================================================
    public function edit(?int $id = null): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        $permissionsTable = $this->fetchTable('Permissions');

        // get() throws RecordNotFoundException (404) if ID doesn't exist
        $permission = $permissionsTable->get($id, contain: []);

        // --- REQUEST: is() checks HTTP method ---
        // is('post')              → POST request
        // is('put') / is('patch') → PUT/PATCH update requests
        // is(['post', 'put'])     → Either POST or PUT
        // is('ajax')              → X-Requested-With: XMLHttpRequest
        // is('json')              → Content-Type: application/json
        if ($this->request->is(['post', 'put', 'patch'])) {
            $permission = $permissionsTable->patchEntity(
                $permission,
                $this->request->getData()
            );

            if ($permissionsTable->save($permission)) {
                $this->Notification->success(__('Permission updated.'));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
            }

            $this->Notification->error(__('Could not update permission.'));
        }

        $this->set(compact('permission'));
        return null;
    }


    // =========================================================================
    // 7. delete() — allowMethod() + Flash + redirect()
    // =========================================================================
    public function delete(?int $id = null): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        // allowMethod() throws MethodNotAllowedException if wrong HTTP verb
        // This prevents deletion via GET requests (e.g. link crawling)
        $this->request->allowMethod(['post', 'delete']);

        $permissionsTable = $this->fetchTable('Permissions');
        $permission = $permissionsTable->get($id);

        if ($permissionsTable->delete($permission)) {
            $this->Notification->success(__('Permission deleted.'));
        } else {
            $this->Notification->error(__('Permission could not be deleted.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    // =========================================================================
    // 8. export() — Download / File Response
    // =========================================================================
    // Demonstrates forcing a file download response.
    //
    // PHASE 4: Download response
    // INTERVIEW: "To force a file download, I build the CSV content as a string
    // and return a Response with Content-Disposition: attachment. CakePHP's
    // Response::withDownload() sets the correct headers automatically."
    // =========================================================================
    public function export(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        // 1. Fetch all permissions for export
        $permissions = $this->fetchTable('Permissions')->find()
            ->contain(['Roles', 'Groups'])
            ->toArray();

        // 2. Build CSV content
        $csv = "ID,Controller,Action,Allowed,Role,Group\n";
        foreach ($permissions as $p) {
            $csv .= sprintf(
                "%d,%s,%s,%s,%s,%s\n",
                $p->id,
                $p->controller,
                $p->action,
                $p->allowed ? 'Yes' : 'No',
                $p->role->name ?? 'N/A',
                $p->group->name ?? 'N/A'
            );
        }

        // 3. Return a download response
        // withDownload() sets: Content-Disposition: attachment; filename="..."
        // withType()     sets: Content-Type header
        // withStringBody() sets the response body
        $response = $this->response
            ->withDownload('permissions-export-' . date('Y-m-d') . '.csv')
            ->withType('text/csv')
            ->withStringBody($csv);

        // 4. Return the Response — do NOT render a view
        return $response;
    }


    // =========================================================================
    // 9. jsonList() — JSON API response
    // =========================================================================
    // PHASE 4: JSON response using JsonView
    // =========================================================================
    public function jsonList(): void
    {
        $this->Authorization->skipAuthorization();

        // Switch to JSON view — no PHP template needed
        $this->viewBuilder()->setClassName('Json');

        $permissions = $this->fetchTable('Permissions')->find()
            ->contain(['Roles', 'Groups'])
            ->limit(50)
            ->toArray();

        $this->set(compact('permissions'));

        // _serialize tells JsonView which view variables to encode
        $this->viewBuilder()->setOption('serialize', ['permissions']);
    }


    // =========================================================================
    // 10. xmlList() — XML API response
    // =========================================================================
    // PHASE 4: XML response using XmlView
    // =========================================================================
    public function xmlList(): void
    {
        $this->Authorization->skipAuthorization();

        // Switch to XML view
        $this->viewBuilder()->setClassName('Xml');

        $permissions = $this->fetchTable('Permissions')->find()
            ->limit(50)
            ->toArray();

        $this->set(compact('permissions'));
        $this->viewBuilder()->setOption('serialize', ['permissions']);
    }


    // =========================================================================
    // 11. bulkImport() — File Upload handling
    // =========================================================================
    // PHASE 4: File Upload via request->getUploadedFiles()
    //
    // INTERVIEW: "File uploads in CakePHP 5 use PSR-7 UploadedFileInterface
    // objects accessible via $this->request->getUploadedFiles(). I validate
    // MIME type, size, and errors before moving the file to its destination."
    // =========================================================================
    public function bulkImport(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();

        if ($this->request->is('post')) {
            // PSR-7 file upload access — returns UploadedFileInterface objects
            $uploadedFiles = $this->request->getUploadedFiles();

            if (empty($uploadedFiles['csv_file'])) {
                $this->Notification->error(__('No file uploaded.'));
                return null;
            }

            /** @var \Psr\Http\Message\UploadedFileInterface $file */
            $file = $uploadedFiles['csv_file'];

            // Check for upload errors (UPLOAD_ERR_OK = 0 means success)
            if ($file->getError() !== UPLOAD_ERR_OK) {
                $this->Notification->error(__('File upload failed with error code: {0}', $file->getError()));
                return null;
            }

            // Validate MIME type — only allow CSV
            $allowedTypes = ['text/csv', 'application/csv', 'text/plain'];
            if (!in_array($file->getClientMediaType(), $allowedTypes)) {
                $this->Notification->error(__('Only CSV files are allowed.'));
                return null;
            }

            // Validate file size — max 2MB
            if ($file->getSize() > 2 * 1024 * 1024) {
                $this->Notification->error(__('File too large. Maximum size is 2MB.'));
                return null;
            }

            // Move uploaded file to a safe location
            $targetPath = WWW_ROOT . 'uploads' . DS . 'imports' . DS . 'permissions_' . time() . '.csv';
            $file->moveTo($targetPath);

            // Parse CSV and insert records
            $imported = 0;
            if (($handle = fopen($targetPath, 'r')) !== false) {
                fgetcsv($handle); // Skip header row
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) >= 4) {
                        $permissionsTable = $this->fetchTable('Permissions');
                        $perm = $permissionsTable->newEntity([
                            'controller' => trim($row[1]),
                            'action'     => trim($row[2]),
                            'allowed'    => trim($row[3]) === 'Yes',
                        ]);
                        if ($permissionsTable->save($perm)) {
                            $imported++;
                        }
                    }
                }
                fclose($handle);
            }

            $this->Notification->success(__('Imported {0} permissions successfully.', $imported));
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

                return $this->redirect(['action' => 'index']);
        }
    }


    // =========================================================================
    // 12. checkAjax() — AJAX + JSON Response example
    // =========================================================================
    // PHASE 4: AJAX pattern — returns JSON when called via XHR
    // Called from the frontend: $.ajax({ url: '/permissions/check-ajax' })
    // =========================================================================
    public function checkAjax(): ?\Cake\Http\Response
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'post']);

        // AJAX requests expect JSON back, not an HTML page
        if ($this->request->is('ajax')) {
            $controller = $this->request->getQuery('controller', '');
            $action     = $this->request->getQuery('action', '');

            $exists = $this->fetchTable('Permissions')->find()
                ->where([
                    'LOWER(Permissions.controller)' => strtolower($controller),
                    'LOWER(Permissions.action)'     => strtolower($action),
                    'Permissions.allowed'           => true,
                ])
                ->count() > 0;

            // Build JSON response manually using Response object
            $data = ['permitted' => $exists, 'controller' => $controller, 'action' => $action];

            $response = $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($data));

            return $response;
        }

        $this->Notification->error(__('This endpoint only accepts AJAX requests.'));
        return $this->redirect(['action' => 'index']);
    }
}
