<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERP API Documentation & Learning Hub</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Swagger UI CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.18.3/swagger-ui.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { margin: 0; background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .nav-tabs .nav-link { color: #495057; font-weight: 500; padding: 12px 20px; }
        .nav-tabs .nav-link.active { font-weight: 600; color: #0d6efd; border-top: 3px solid #0d6efd; }
        .tab-content { background: #fff; border: 1px solid #dee2e6; border-top: none; padding: 20px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .header { background: #1e293b; color: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .mermaid { display: flex; justify-content: center; overflow: auto; background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; }
    </style>
</head>
<body>
    
    <div class="header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> ERP Live Learning Hub</h4>
        <span class="badge bg-success">CakePHP 5 Engine Running</span>
    </div>

    <div class="container-fluid mt-4 px-4">
        <ul class="nav nav-tabs" id="learningHubTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="swagger-tab" data-bs-toggle="tab" data-bs-target="#swagger-panel" type="button" role="tab"><i class="fa-solid fa-server me-1"></i> Live API Sandbox</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schema-tab" data-bs-toggle="tab" data-bs-target="#schema-panel" type="button" role="tab"><i class="fa-solid fa-diagram-project me-1"></i> Data Model Graph</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events-panel" type="button" role="tab"><i class="fa-solid fa-bolt me-1"></i> Event Lifecycle (ORM)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced-panel" type="button" role="tab"><i class="fa-solid fa-layer-group me-1"></i> Enterprise Architecture (Senior Level)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="fundamentals-tab" data-bs-toggle="tab" data-bs-target="#fundamentals-panel" type="button" role="tab"><i class="fa-solid fa-book-open me-1"></i> CakePHP 5 Fundamentals</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schema-guide-tab" data-bs-toggle="tab" data-bs-target="#schema-guide-panel" type="button" role="tab"><i class="fa-solid fa-table me-1"></i> Full Project Schema</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-danger" id="interview-tab" data-bs-toggle="tab" data-bs-target="#interview-panel" type="button" role="tab"><i class="fa-solid fa-fire me-1"></i> Interview Prep (10 YoE)</button>
            </li>
        </ul>
        
        <div class="tab-content" id="learningHubTabsContent">
            
            <!-- SWAGGER UI TAB -->
            <div class="tab-pane fade show active" id="swagger-panel" role="tabpanel">
                <div class="alert alert-info border-0 shadow-sm rounded-3">
                    <i class="fa-solid fa-circle-info me-2"></i> <strong>Pro Tip:</strong> Authenticate via <code>/auth/login.json</code> with <strong>admin / password</strong> to receive a JWT Token. Paste it in the "Authorize" button to access secured endpoints.
                </div>
                <div id="swagger-ui"></div>
            </div>

            <!-- SCHEMA GRAPH TAB -->
            <div class="tab-pane fade" id="schema-panel" role="tabpanel">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light fw-bold"><i class="fa-solid fa-database me-2"></i> Core Modules</div>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action active" onclick="showGraph('catalog')">Catalog & Inventory</a>
                                <a href="#" class="list-group-item list-group-item-action" onclick="showGraph('orders')">Order Management</a>
                                <a href="#" class="list-group-item list-group-item-action" onclick="showGraph('auth')">Users & Auth (RBAC)</a>
                                <a href="#" class="list-group-item list-group-item-action" onclick="showGraph('comms')">Comms & CMS</a>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 bg-info bg-opacity-10">
                            <div class="card-header bg-info text-dark fw-bold"><i class="fa-solid fa-link me-2"></i> ORM Associations</div>
                            <div class="card-body small">
                                <strong>belongsTo:</strong> Foreign key is in current table (e.g. Products <code>belongsTo</code> Brands).<br><br>
                                <strong>hasMany:</strong> Foreign key is in the other table (e.g. Orders <code>hasMany</code> OrderItems).<br><br>
                                <strong>belongsToMany:</strong> Requires a join table (e.g. Users <code>belongsToMany</code> Groups via <code>group_users</code>).<br><br>
                                <strong>hasOne:</strong> 1-to-1 relation, foreign key in other table (e.g. Orders <code>hasOne</code> Invoices).
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="fa-solid fa-project-diagram me-2"></i> Interactive ER Diagram</span>
                                <span class="badge bg-secondary" id="current-module-badge">Catalog Module</span>
                            </div>
                            <div class="card-body bg-light rounded-bottom d-flex align-items-center justify-content-center" style="min-height: 350px;">
                                <div id="mermaid-container" class="mermaid w-100 text-center">
                                    <!-- Graph renders here -->
                                </div>
                            </div>
                        </div>

                        <!-- Module Details Panel -->
                        <div class="card shadow-sm border-0 border-start border-4 border-primary" id="module-details-panel">
                            <div class="card-body">
                                <h5 class="fw-bold text-primary" id="module-title">Catalog & Inventory Module</h5>
                                <p class="text-muted" id="module-description">This module is the core of the e-commerce system. It handles nested category trees and tracks physical product inventory.</p>
                                <hr>
                                <h6><strong>Key Technical Highlights:</strong></h6>
                                <ul id="module-highlights" class="small text-muted mb-0">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EVENTS TAB -->
            <div class="tab-pane fade" id="events-panel" role="tabpanel">
                
                <div class="alert alert-primary border-0 shadow-sm rounded-3 mb-4">
                    <i class="fa-solid fa-layer-group me-2"></i> <strong>Senior Concept: The CakePHP ORM Lifecycle</strong> 
                    <br>CakePHP doesn't just "save data". Every operation flows through a rigorous, event-driven pipeline. You can hook into these events globally, locally, or via Reusable Behaviors.
                </div>

                <div class="row g-4">
                    <!-- Basic Concept -->
                    <div class="col-md-12">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-info">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-seedling me-2 text-info"></i> Basic Level: What is an ORM Event?</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Events are simply hooks or callbacks that trigger automatically before or after database operations. Instead of writing all your logic in the Controller, you move it to the Table class where it runs automatically every time data is saved.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded">
                                            <strong>Without Events (Bad/Junior):</strong><br>
                                            <code>
                                            $user->password = hash($password);<br>
                                            $user->created = now();<br>
                                            $this->Users->save($user);
                                            </code>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded">
                                            <strong>With Events (Good/Standard):</strong><br>
                                            <code>
                                            // In UsersTable.php beforeSave()<br>
                                            $entity->password = hash($password);<br>
                                            // $entity->created is handled by TimestampBehavior automatically!
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- The Full Save Lifecycle -->
                    <div class="col-md-12">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-dark text-white fw-bold"><i class="fa-solid fa-arrows-spin me-2"></i> 1. The Complete Entity Save Lifecycle</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">This diagram shows the exact sequence of events triggered when you call <code>$table->save($entity)</code>. This is where advanced logic like Slugging, Auditing, and Stock Deduction live.</p>
                                <div class="mermaid">
                                    stateDiagram-v2
                                        direction LR
                                        [*] --> beforeMarshal: patchEntity()
                                        beforeMarshal --> EntityCreated
                                        
                                        state "Table->save()" as Save {
                                            direction TB
                                            beforeRules --> afterRules: CheckUnique / Constraints
                                            afterRules --> beforeSave: Business Logic
                                            beforeSave --> SQL_INSERT_UPDATE: Database Execution
                                            SQL_INSERT_UPDATE --> afterSave: Post-Save Logic
                                            afterSave --> afterSaveCommit: Transaction Committed
                                        }
                                        
                                        EntityCreated --> Save
                                        Save --> [*]
                                        
                                        note right of beforeMarshal: Used by: PasswordHasher
                                        note right of beforeRules: Used by: CheckUnique Rule
                                        note right of beforeSave: Used by: Sluggable, Timestamp
                                        note right of afterSave: Used by: CounterCache, AuditLog
                                        note right of afterSaveCommit: Used by: Queue Background Jobs
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Behavior Integration -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-success text-white fw-bold"><i class="fa-solid fa-puzzle-piece me-2"></i> 2. Advanced ORM Behaviors</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">How reusable Behaviors hook into the event system without cluttering Table classes.</p>
                                <div class="mermaid">
                                    flowchart TD
                                        T[ProductsTable] -->|Triggers| E(Event: Model.beforeSave)
                                        E -->|Listens| B1[Timestamp Behavior]
                                        E -->|Listens| B2[Sluggable Behavior]
                                        
                                        B1 -->|Sets| C1(created/modified fields)
                                        B2 -->|Sets| C2(slug field)
                                        
                                        C1 --> D[(Database)]
                                        C2 --> D
                                        
                                        T2[OrdersTable] -->|Triggers| E2(Event: Model.afterSave)
                                        E2 -->|Listens| B3[CounterCache Behavior]
                                        B3 -->|Updates| T3[UsersTable total_orders count]
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complex Query Generation -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white fw-bold"><i class="fa-solid fa-magnifying-glass-chart me-2"></i> 3. Advanced Query Generation & Eager Loading</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">How CakePHP optimizes complex relational queries using the <code>contain()</code> eager loader to solve the N+1 query problem.</p>
                                <div class="mermaid">
                                    sequenceDiagram
                                        participant C as Controller
                                        participant O as OrdersTable
                                        participant U as UsersTable
                                        participant I as OrderItemsTable
                                        participant DB as Database
                                        
                                        C->>O: find()->contain(['Users', 'OrderItems.Products'])
                                        O->>DB: SELECT * FROM orders LIMIT 20
                                        DB-->>O: (20 Order Records)
                                        O->>U: Eager Load (BelongsTo)
                                        U->>DB: SELECT * FROM users WHERE id IN (1, 5, 8...)
                                        O->>I: Eager Load (HasMany)
                                        I->>DB: SELECT * FROM order_items WHERE order_id IN (...)
                                        DB-->>I: (All related items)
                                        O->>O: ORM stitches arrays into Nested Entities
                                        O-->>C: Returns clean Object Hierarchy
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-warning text-dark fw-bold"><i class="fa-solid fa-shield-halved me-2"></i> 4. Application-Wide Audit Logging (Global EventManager)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Using the Global EventManager to track changes across <strong>all</strong> modules without modifying individual tables.</p>
                                <div class="mermaid">
                                    sequenceDiagram
                                        participant AnyController
                                        participant AnyTable
                                        participant GlobalEventManager
                                        participant AuditLogsTable
                                        
                                        AnyController->>AnyTable: save($entity)
                                        AnyTable->>AnyTable: Validation & Rules
                                        AnyTable->>AnyTable: afterSave Event Dispatched
                                        AnyTable-->>GlobalEventManager: Event: Model.afterSave
                                        GlobalEventManager->>AuditLogsTable: extract dirty fields ($entity->getDirty())
                                        GlobalEventManager->>AuditLogsTable: save(AuditLog)
                                        AuditLogsTable-->>GlobalEventManager: (Logged implicitly)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Find/Read Lifecycle -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-secondary text-white fw-bold"><i class="fa-solid fa-filter me-2"></i> 5. The Query (Find) Lifecycle</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Events triggered when reading data. Essential for global scopes (e.g. SoftDelete) and formatting results.</p>
                                <div class="mermaid">
                                    flowchart TD
                                        A[Controller: Table->find()] --> B(Event: Model.beforeFind)
                                        B -->|SoftDelete Behavior adds WHERE deleted IS NULL| C[Query Execution]
                                        C --> D(Formatters / MapReduce)
                                        D --> E(Event: Model.afterFind)
                                        E --> F[Return ResultSet / Entities]
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Model 360 -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-danger text-white fw-bold"><i class="fa-solid fa-network-wired me-2"></i> 6. User Model 360° Data Flow</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">A complete view of how ONE model (Users) connects to APIs, Database Relations, and ORM Events simultaneously.</p>
                                <div class="mermaid">
                                    flowchart LR
                                        subgraph API ["API & HTTP"]
                                            Login[POST /auth/login]
                                            Profile[GET /users/profile]
                                        end
                                        
                                        subgraph ORM ["UsersTable ORM Events"]
                                            BM[beforeMarshal]
                                            BS[beforeSave]
                                        end
                                        
                                        subgraph DB ["Database Relations"]
                                            U[(users)]
                                            R[(roles)]
                                            O[(orders)]
                                            T[(api_tokens)]
                                        end
                                        
                                        Login -->|AuthenticationMiddleware| U
                                        Profile -->|find()| U
                                        
                                        U -->|belongsTo| R
                                        U -->|hasMany| O
                                        U -->|hasMany| T
                                        
                                        BM -.->|Hashes Password| U
                                        BS -.->|Generates UUID| U
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Master Level: Custom Event Dispatching -->
                    <div class="col-md-12">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-warning">
                            <div class="card-header bg-dark text-warning fw-bold"><i class="fa-solid fa-crown me-2"></i> Master Level: Custom Domain Event Dispatching</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Instead of just relying on CakePHP's built-in ORM events (like <code>beforeSave</code>), Master developers create their own Domain Events (like <code>Order.placed</code>) to completely decouple the system architecture.</p>
                                <div class="mermaid">
                                    sequenceDiagram
                                        participant C as OrdersController
                                        participant EM as EventManager
                                        participant L1 as EmailListener
                                        participant L2 as PointsListener
                                        participant L3 as WebhookListener
                                        
                                        C->>C: Order saved successfully
                                        C->>EM: dispatch('Order.placed', [order])
                                        
                                        EM-->>L1: notify()
                                        L1->>L1: Send Confirmation Email
                                        
                                        EM-->>L2: notify()
                                        L2->>L2: Add Loyalty Points to User
                                        
                                        EM-->>L3: notify()
                                        L3->>L3: Send Slack Notification
                                        
                                        Note over C,L3: The Controller doesn't know about emails, points, or Slack.<br/>It just announces "An order was placed" and the Listeners react.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ADVANCED ARCHITECTURE TAB -->
            <div class="tab-pane fade" id="advanced-panel" role="tabpanel">
                <div class="row g-4">
                    <!-- Queue System -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-dark text-white fw-bold"><i class="fa-solid fa-server me-2"></i> 1. Message Queue & Background Jobs</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Decoupling heavy tasks (like email and report generation) from the HTTP request to prevent timeouts and handle high traffic.</p>
                                <div class="mermaid">
                                    sequenceDiagram
                                        participant Client
                                        participant API (Controller)
                                        participant Queue (Redis/DB)
                                        participant CLI Worker Daemon
                                        
                                        Client->>API (Controller): POST /orders
                                        API (Controller)->>API (Controller): Validate & Save Order
                                        API (Controller)->>Queue (Redis/DB): push(OrderEmailJob)
                                        API (Controller)-->>Client: 201 Created (50ms response)
                                        
                                        loop Every second
                                            CLI Worker Daemon->>Queue (Redis/DB): Poll for jobs
                                            Queue (Redis/DB)-->>CLI Worker Daemon: pop(OrderEmailJob)
                                            CLI Worker Daemon->>CLI Worker Daemon: Generate PDF & Send Email
                                        end
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Read/Write Splitting -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-danger text-white fw-bold"><i class="fa-solid fa-database me-2"></i> 2. DB Read/Write Replica Splitting</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Directing SELECT queries to Read Replicas and INSERT/UPDATE queries to the Primary DB to scale database throughput.</p>
                                <div class="mermaid">
                                    flowchart TD
                                        A[Application / ORM] -->|INSERT, UPDATE, DELETE| B[(Primary Database - Master)]
                                        A -->|SELECT, find()| C[(Read Replica 1)]
                                        A -->|SELECT, find()| D[(Read Replica 2)]
                                        
                                        B -.->|Async Replication| C
                                        B -.->|Async Replication| D
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Layer Pattern -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-info text-dark fw-bold"><i class="fa-solid fa-layer-group me-2"></i> 3. Service Layer / Command Bus</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Moving business logic out of controllers into dedicated, testable service classes.</p>
                                <div class="mermaid">
                                    flowchart LR
                                        C[OrdersController] -->|Injects| S(OrderService)
                                        C -->|Injects| I(InvoiceService)
                                        
                                        S -->|Validates| T[(Orders Table)]
                                        S -->|Updates| P[(Products Table - Stock)]
                                        
                                        I -->|Generates PDF| F[File System]
                                        I -->|Saves Record| IT[(Invoices Table)]
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Caching -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-success text-white fw-bold"><i class="fa-solid fa-memory me-2"></i> 4. Cache Tagging & Invalidation</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Using Redis Cache tags to group related queries, and busting them automatically on save.</p>
                                <div class="mermaid">
                                    sequenceDiagram
                                        participant Client
                                        participant API
                                        participant Redis Cache
                                        participant Database
                                        
                                        Client->>API: GET /products (List)
                                        API->>Redis Cache: read('catalog_products')
                                        Redis Cache-->>API: Miss
                                        API->>Database: SELECT * FROM products
                                        Database-->>API: Results
                                        API->>Redis Cache: write('catalog_products') with tag 'catalog'
                                        
                                        Client->>API: PUT /products/1 (Edit)
                                        API->>Database: UPDATE products...
                                        API->>Redis Cache: clearGroup('catalog')
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middleware Pipeline -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white fw-bold"><i class="fa-solid fa-shield-cat me-2"></i> 5. HTTP Middleware Stack (PSR-15)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">The request/response lifecycle. Every request passes through security and parsing layers before hitting the controller.</p>
                                <div class="mermaid">
                                    flowchart TD
                                        Req([HTTP Request]) --> A(Error Handler Middleware)
                                        A --> B(Asset Middleware)
                                        B --> C(Routing Middleware)
                                        C --> D(Body Parser Middleware)
                                        D --> E(Authentication Middleware)
                                        E --> F(Authorization Middleware)
                                        F --> Controller{Controller Action}
                                        Controller --> |Returns Data| F
                                        F --> |Response| E
                                        E --> |Response| D
                                        D --> |Response| C
                                        C --> |Response| B
                                        B --> |Response| A
                                        A --> Res([HTTP Response])
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dependency Injection Container -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-warning text-dark fw-bold"><i class="fa-solid fa-box-open me-2"></i> 6. Dependency Injection Container (DIC)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">CakePHP 5 automatically resolves class dependencies and injects them into controller methods via Auto-wiring.</p>
                                <div class="mermaid">
                                    flowchart LR
                                        DIC{CakePHP DIC}
                                        C[OrdersController::add]
                                        S[OrderService]
                                        E[EmailService]
                                        
                                        DIC -.->|Auto-wires| E
                                        DIC -.->|Auto-wires| S
                                        
                                        E -->|Injected into constructor| S
                                        S -->|Injected into action| C
                                        
                                        Client([HTTP Route]) --> C
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FUNDAMENTALS TAB -->
            <div class="tab-pane fade" id="fundamentals-panel" role="tabpanel">
                <div class="alert alert-secondary border-0 shadow-sm rounded-3 mb-4">
                    <i class="fa-solid fa-graduation-cap me-2"></i> <strong>CakePHP 5 Fundamentals</strong> 
                    <br>Mastering the framework means understanding conventions, MVC flow, and core configuration. Here is the foundation of the ERP.
                </div>
                
                <div class="row g-4">
                    <!-- MVC Flow -->
                    <div class="col-md-12">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white fw-bold"><i class="fa-solid fa-code-branch me-2"></i> 1. The MVC Request Cycle</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">How a browser request flows through CakePHP (Model-View-Controller).</p>
                                <div class="mermaid">
                                    flowchart LR
                                        Browser((Browser)) -->|HTTP GET /products| Router["routes.php"]
                                        Router -->|Routes to| Controller["ProductsController"]
                                        Controller -->|Calls find()| Table["ProductsTable (Model)"]
                                        Table -->|Queries| DB[(Database)]
                                        DB -->|Returns Data| Table
                                        Table -->|Returns Entities| Controller
                                        Controller -->|Sets Data via set()| View["Template: index.php"]
                                        View -->|Renders HTML| Browser
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Naming Conventions -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-dark text-white fw-bold"><i class="fa-solid fa-spell-check me-2"></i> 2. Naming Conventions (Magic)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">CakePHP relies heavily on "Convention over Configuration". If you name things correctly, everything auto-wires.</p>
                                <table class="table table-sm table-bordered mt-2">
                                    <thead class="table-light">
                                        <tr><th>Component</th><th>Convention</th><th>Example</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td><strong>Database Table</strong></td><td>Lowercase, Plural</td><td><code>contact_enquiries</code></td></tr>
                                        <tr><td><strong>Table Class</strong></td><td>CamelCase, Plural + "Table"</td><td><code>ContactEnquiriesTable</code></td></tr>
                                        <tr><td><strong>Entity Class</strong></td><td>CamelCase, Singular</td><td><code>ContactEnquiry</code></td></tr>
                                        <tr><td><strong>Controller</strong></td><td>CamelCase, Plural + "Controller"</td><td><code>ContactEnquiriesController</code></td></tr>
                                        <tr><td><strong>Foreign Key</strong></td><td>singular_table_id</td><td><code>category_id</code></td></tr>
                                        <tr><td><strong>Join Table (HABTM)</strong></td><td>alphabetical_order</td><td><code>group_users</code></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- The Table vs Entity -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-info text-dark fw-bold"><i class="fa-solid fa-table-columns me-2"></i> 3. Table vs. Entity</div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">CakePHP uses the DataMapper pattern. Models are split into two classes:</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Table (The Collection):</strong> <code>ProductsTable</code><br>
                                        Handles finding records, saving, deleting, and validating. Represents the whole table.
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Entity (The Row):</strong> <code>Product</code><br>
                                        Represents a single row of data. Contains virtual properties, hidden fields (like passwords), and formatting logic.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Core Database Relationships -->
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0 border-start border-4 border-danger">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-diagram-project me-2 text-danger"></i> 4. The 4 Core Database Relationships (Associations)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-4">Understanding where to put the Foreign Key is the most critical part of relational database design. CakePHP handles all JOINs automatically if you follow these 4 rules.</p>
                                
                                <div class="row g-4">
                                    <!-- belongsTo -->
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="fw-bold text-primary">1. Many-to-One (belongsTo)</h6>
                                            <p class="small">The Foreign Key is in the <strong>CURRENT</strong> table.</p>
                                            <div class="mermaid">
                                                erDiagram
                                                    PRODUCTS {
                                                        int id PK
                                                        string name
                                                        int category_id FK
                                                    }
                                                    CATEGORIES {
                                                        int id PK
                                                        string name
                                                    }
                                                    PRODUCTS }|--|| CATEGORIES : belongsTo
                                            </div>
                                            <code class="small">$this->belongsTo('Categories');</code>
                                        </div>
                                    </div>

                                    <!-- hasMany -->
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="fw-bold text-success">2. One-to-Many (hasMany)</h6>
                                            <p class="small">The Foreign Key is in the <strong>OTHER</strong> table.</p>
                                            <div class="mermaid">
                                                erDiagram
                                                    CATEGORIES {
                                                        int id PK
                                                        string name
                                                    }
                                                    PRODUCTS {
                                                        int id PK
                                                        string name
                                                        int category_id FK
                                                    }
                                                    CATEGORIES ||--|{ PRODUCTS : hasMany
                                            </div>
                                            <code class="small">$this->hasMany('Products');</code>
                                        </div>
                                    </div>

                                    <!-- hasOne -->
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="fw-bold text-warning">3. One-to-One (hasOne)</h6>
                                            <p class="small">A strict 1:1 relationship. The Foreign Key is in the <strong>OTHER</strong> table (made unique).</p>
                                            <div class="mermaid">
                                                erDiagram
                                                    USERS {
                                                        int id PK
                                                        string username
                                                    }
                                                    USER_PROFILES {
                                                        int id PK
                                                        int user_id FK
                                                        string address
                                                    }
                                                    USERS ||--|| USER_PROFILES : hasOne
                                            </div>
                                            <code class="small">$this->hasOne('UserProfiles');</code>
                                        </div>
                                    </div>

                                    <!-- belongsToMany -->
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="fw-bold text-danger">4. Many-to-Many (belongsToMany)</h6>
                                            <p class="small">Requires a 3rd <strong>JOIN TABLE</strong> containing both Foreign Keys.</p>
                                            <div class="mermaid">
                                                erDiagram
                                                    USERS {
                                                        int id PK
                                                        string username
                                                    }
                                                    GROUP_USERS {
                                                        int user_id FK
                                                        int group_id FK
                                                    }
                                                    GROUPS {
                                                        int id PK
                                                        string name
                                                    }
                                                    USERS }o--o{ GROUPS : belongsToMany
                                            </div>
                                            <code class="small">$this->belongsToMany('Groups');</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ERP SCHEMA GUIDE TAB -->
            <div class="tab-pane fade" id="schema-guide-panel" role="tabpanel">
                <div class="alert alert-dark border-0 shadow-sm rounded-3 mb-4">
                    <i class="fa-solid fa-database me-2"></i> <strong>Full ERP Database Schema & Logic Guide</strong> 
                    <br>This tab maps every actual table in the <code>cakephp_enterprise</code> database to its CakePHP relationship, complete with the business logic you need to explain in an interview.
                </div>

                <div class="row g-4">
                    <!-- Many-to-One -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-primary">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-arrow-right-to-bracket me-2 text-primary"></i> 1. Many-to-One (belongsTo)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-3"><em>Logic: The current table holds the `_id` foreign key. It belongs to a parent table.</em></p>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item"><strong><code>products</code> belongsTo <code>categories</code></strong><br><span class="text-muted">A product sits inside one category. (FK: <code>category_id</code>)</span></li>
                                    <li class="list-group-item"><strong><code>products</code> belongsTo <code>brands</code></strong><br><span class="text-muted">A product is manufactured by one brand. (FK: <code>brand_id</code>)</span></li>
                                    <li class="list-group-item"><strong><code>order_items</code> belongsTo <code>orders</code></strong><br><span class="text-muted">A line item belongs to a specific parent order. (FK: <code>order_id</code>)</span></li>
                                    <li class="list-group-item"><strong><code>order_items</code> belongsTo <code>products</code></strong><br><span class="text-muted">A line item represents a specific product in the catalog. (FK: <code>product_id</code>)</span></li>
                                    <li class="list-group-item"><strong><code>users</code> belongsTo <code>roles</code></strong><br><span class="text-muted">An employee has a specific role (e.g., Admin). (FK: <code>role_id</code>)</span></li>
                                    <li class="list-group-item"><strong><code>invoices</code> belongsTo <code>orders</code></strong><br><span class="text-muted">An invoice is generated for a specific order. (FK: <code>order_id</code>)</span></li>
                                    <li class="list-group-item"><strong><code>activity_logs</code> belongsTo <code>users</code></strong><br><span class="text-muted">A tracked action was performed by a specific user. (FK: <code>user_id</code>)</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- One-to-Many -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-success">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-arrows-split-up-and-left me-2 text-success"></i> 2. One-to-Many (hasMany)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-3"><em>Logic: The current table is the "Parent". It has many "Children" (the children hold the foreign key).</em></p>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item">
                                        <strong><code>categories</code> hasMany <code>products</code></strong><br>
                                        <span class="text-primary"><i class="fa-solid fa-comment-dots"></i> Interview Logic:</span> "I use hasMany so that when I delete a Category, I can automatically cascade the delete to all its products, or re-assign them."
                                    </li>
                                    <li class="list-group-item">
                                        <strong><code>orders</code> hasMany <code>order_items</code></strong><br>
                                        <span class="text-primary"><i class="fa-solid fa-comment-dots"></i> Interview Logic:</span> "This is critical. I use this to calculate the <code>$order->total</code> by iterating through the hasMany items during the <code>beforeSave</code> event."
                                    </li>
                                    <li class="list-group-item">
                                        <strong><code>roles</code> hasMany <code>users</code></strong><br>
                                        <span class="text-muted">One role is assigned to many employees.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- One-to-One -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-warning">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-link me-2 text-warning"></i> 3. One-to-One (hasOne)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-3"><em>Logic: A strict 1:1 pairing. The other table holds the unique foreign key.</em></p>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item">
                                        <strong><code>orders</code> hasOne <code>invoices</code></strong><br>
                                        <span class="text-primary"><i class="fa-solid fa-comment-dots"></i> Interview Logic:</span> "I use <code>hasOne</code> here instead of hasMany to enforce strict financial compliance. An order cannot be double-invoiced in this ERP at the database level."
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Many-to-Many -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-network-wired me-2 text-danger"></i> 4. Many-to-Many (belongsToMany)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-3"><em>Logic: Requires a 3rd JOIN TABLE because both sides have many of each other.</em></p>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item">
                                        <strong><code>users</code> belongsToMany <code>groups</code></strong> (via <code>group_users</code>)<br>
                                        <span class="text-primary"><i class="fa-solid fa-comment-dots"></i> Interview Logic:</span> "For RBAC, I implemented a <code>belongsToMany</code> relation. This allows HR to assign multiple dynamic permissions to a user without altering their core <code>role_id</code>."
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Tables -->
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0 bg-light">
                            <div class="card-header bg-secondary text-white fw-bold"><i class="fa-solid fa-microchip me-2"></i> Advanced Feature Tables (Global Logic)</div>
                            <div class="card-body small">
                                <p>Some tables act globally and don't use standard direct foreign keys:</p>
                                <ul>
                                    <li><strong><code>audit_logs</code></strong>: Tracks changes across *every* table using <code>model_name</code> and <code>foreign_key</code>. This is called a <strong>Polymorphic Relation</strong>.</li>
                                    <li><strong><code>settings</code></strong>: Key-value pairs for system configuration (e.g., <code>tax_rate = 18%</code>). Read globally by the Cache engine.</li>
                                    <li><strong><code>queued_jobs</code> / <code>failed_jobs</code></strong>: Handled purely by the CLI Queue Worker Daemon, isolated from standard web traffic relations.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- INTERVIEW PREP TAB -->
            <div class="tab-pane fade" id="interview-panel" role="tabpanel">
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <i class="fa-solid fa-fire me-2"></i> <strong>10 Years Experience Interview Masterclass</strong> 
                    <br>This tab covers the evolution of CakePHP from v2 to v5, core PHP fundamentals, and tricky database questions to prove your seniority.
                </div>

                <div class="row g-4">
                    <!-- CakePHP Evolution -->
                    <div class="col-md-12">
                        <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger">
                            <div class="card-header bg-white fw-bold"><i class="fa-solid fa-timeline me-2 text-danger"></i> 1. CakePHP Evolution (v2 -> v5)</div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">When you claim 10 years of experience, interviewers will test if you actually lived through the framework migrations.</p>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Version (Era)</th>
                                                <th>Major Architecture Changes (What you must say)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>CakePHP 2.x</strong><br><small class="text-muted">(2011 - 2014)</small></td>
                                                <td>
                                                    <ul>
                                                        <li><strong>No Namespaces:</strong> Used old PHP 5.2 class loading (e.g. <code>App::uses()</code>).</li>
                                                        <li><strong>Array-based ORM:</strong> The ORM returned massive nested arrays (<code>$data['User']['name']</code>) instead of Objects, which was a nightmare for memory and autocompletion.</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>CakePHP 3.x</strong><br><small class="text-muted">(2015 - 2019)</small></td>
                                                <td>
                                                    <ul>
                                                        <li><strong>The Great Rewrite:</strong> Moved to PHP 5.4+ Namespaces and Composer (PSR-4).</li>
                                                        <li><strong>DataMapper ORM:</strong> Replaced the old array ORM with the modern <strong>Table / Entity</strong> architecture we use today. Queries finally returned proper Objects.</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>CakePHP 4.x</strong><br><small class="text-muted">(2019 - 2023)</small></td>
                                                <td>
                                                    <ul>
                                                        <li><strong>Strict Typing:</strong> Enforced PHP 7.2+ strict types (<code>declare(strict_types=1);</code>) and return type hints everywhere.</li>
                                                        <li><strong>Middleware:</strong> Fully embraced PSR-15 HTTP Middleware, killing the old Dispatcher system.</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>CakePHP 5.x</strong><br><small class="text-muted">(2023 - Present)</small></td>
                                                <td>
                                                    <ul>
                                                        <li><strong>PHP 8.1+ Only:</strong> Uses modern PHP features like Enums, Attributes, and Constructor Property Promotion.</li>
                                                        <li><strong>Dependency Injection Container (DIC):</strong> Introduced true auto-wiring, allowing us to inject Services directly into Controller actions without using <code>new Class()</code>.</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deep Dive Accordion Sections -->
                    <div class="col-md-12 mt-2">
                        <div class="accordion shadow-sm" id="interviewAccordion">

                            <!-- Syllabus Map -->
                            <div class="accordion-item border-0 border-start border-4 border-dark mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#syllabusMap">
                                        <i class="fa-solid fa-book-journal-whills me-2 text-dark"></i> Complete Master Syllabus Map (5,000+ Questions Outline)
                                    </button>
                                </h2>
                                <div id="syllabusMap" class="accordion-collapse collapse" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="alert alert-secondary border-0 mb-3">
                                            <strong><i class="fa-solid fa-crosshairs text-danger me-2"></i> Target Audience:</strong> Senior Developer, Lead Developer, Technical Lead
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Volume 1: PHP Mastery (500+ Qs)</strong>
                                                <ul class="mb-1 text-muted">
                                                    <li><strong>Core:</strong> Request Lifecycle, Data Types, Arrays, References.</li>
                                                    <li><strong>OOP:</strong> Traits, Interfaces, SPL, Generators, Enums, Attributes.</li>
                                                    <li><strong>Internals:</strong> Zend Engine, OPcache, JIT, Garbage Collection.</li>
                                                    <li><strong>Patterns:</strong> SOLID, Factory, Service Layer, Dependency Injection.</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Volume 2: CakePHP Complete (1,000+ Qs)</strong>
                                                <ul class="mb-1 text-muted">
                                                    <li><strong>MVC & ORM:</strong> Routing, Behaviors, Events, Validators, Pagination.</li>
                                                    <li><strong>Auth:</strong> JWT, ACL, Role-Based Access, Security Headers.</li>
                                                    <li><strong>Background:</strong> Queue Plugin, RabbitMQ, Mailer, Redis Cache.</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Volume 3: MySQL & PostgreSQL (500+ Qs)</strong>
                                                <ul class="mb-1 text-muted">
                                                    <li><strong>Architecture:</strong> ACID, Transactions, Isolation Levels, Deadlocks.</li>
                                                    <li><strong>Optimization:</strong> Composite Indexes, EXPLAIN Plans, Partitioning.</li>
                                                    <li><strong>Advanced:</strong> Window Functions, CTEs, JSON columns, Triggers.</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Volume 4 & 5 & 6: REST, Linux, Git (300+ Qs)</strong>
                                                <ul class="mb-1 text-muted">
                                                    <li><strong>API:</strong> OAuth2, Rate Limiting, Idempotency, Versioning.</li>
                                                    <li><strong>DevOps:</strong> Nginx, PHP-FPM tuning, Docker Basics, Systemctl.</li>
                                                    <li><strong>Git:</strong> Rebase vs Merge, Cherry-pick, Git Flow.</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-12 border-top pt-3 mt-3">
                                                <strong>Volume 7, 8 & 9: Real System Design & Coding (200+ Scenarios)</strong>
                                                <p class="text-muted mb-0">Microservices vs Monoliths, Message Queues (RabbitMQ), Load Balancers, Horizontal Scaling, Building Payment Gateways, Parsing CSVs with Yield, and handling Live Production Crashes.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 2. PHP 8 Fundamentals -->
                            <div class="accordion-item border-0 border-start border-4 border-info mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#phpFundamentals">
                                        <i class="fa-brands fa-php me-2 text-info"></i> 2. PHP 8 Fundamentals & Architecture
                                    </button>
                                </h2>
                                <div id="phpFundamentals" class="accordion-collapse collapse show" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Traits vs Interfaces vs Abstract Classes:</strong><br>
                                                - <em>Interface:</em> A contract. Forces a class to implement specific methods, but holds no logic.<br>
                                                - <em>Abstract Class:</em> A base class that cannot be instantiated. Can hold *some* logic and properties to be inherited.<br>
                                                - <em>Trait:</em> A copy-paste mechanism (Horizontal Reuse). Used to share methods across unrelated classes (like CakePHP's <code>TimestampBehavior</code> mapping to a trait).
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Dependency Injection (DI):</strong><br>
                                                Instead of a class creating its own dependencies (<code>$db = new Database()</code>), you pass them in through the constructor. This makes unit testing incredibly easy because you can pass a mock/fake database instead of hitting the real one.
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Static vs Self vs $this:</strong><br>
                                                - <code>$this</code> refers to the current object instance.<br>
                                                - <code>self::</code> binds to the class where it is written (early binding).<br>
                                                - <code>static::</code> binds to the class that called it at runtime (Late Static Binding). Crucial for inheritance in ORMs.
                                            </div>
                                            <div class="col-md-6">
                                                <strong>PHP 8 Features (Enums & Match):</strong><br>
                                                - <em>Enums:</em> Replace arbitrary strings (like "pending", "shipped") with strictly typed objects, preventing invalid state bugs.<br>
                                                - <em>Match expression:</em> A strict, returnable version of <code>switch</code> that doesn't require <code>break;</code> statements and does strict type comparison (<code>===</code>).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. CakePHP Specifics -->
                            <div class="accordion-item border-0 border-start border-4 border-danger mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#cakephpSpecifics">
                                        <i class="fa-solid fa-layer-group me-2 text-danger"></i> 3. CakePHP Senior Architecture
                                    </button>
                                </h2>
                                <div id="cakephpSpecifics" class="accordion-collapse collapse" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Components vs Behaviors vs Helpers:</strong><br>
                                                - <em>Components:</em> Shared logic between <strong>Controllers</strong> (e.g., AuthComponent, FlashComponent).<br>
                                                - <em>Behaviors:</em> Shared logic between <strong>Table Models</strong> (e.g., TimestampBehavior, TreeBehavior). Modifies queries and saves.<br>
                                                - <em>Helpers:</em> Shared logic between <strong>Views/Templates</strong> (e.g., FormHelper, HtmlHelper). Formats UI data.
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Routing & Reverse Routing:</strong><br>
                                                Instead of hardcoding URLs (<code>/users/view/1</code>), we use array routing (<code>['controller' => 'Users', 'action' => 'view', 1]</code>). If the route prefix or URL structure changes in <code>routes.php</code>, the entire application updates automatically without broken links.
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Fat Models, Skinny Controllers:</strong><br>
                                                Controllers should only handle HTTP requests (get parameters, call model, return JSON/HTML). ALL business logic, validation, and data manipulation must live in the Table classes or a dedicated Service Layer (like <code>OrderService.php</code>).
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Table vs Entity:</strong><br>
                                                - <em>Table:</em> Represents the collection of data (the database table itself). Handles <code>find()</code>, <code>save()</code>, and relationships.<br>
                                                - <em>Entity:</em> Represents a single row of data. Handles virtual fields, formatting, and hidden fields (like hiding passwords from JSON output).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. MySQL & Database Mastery -->
                            <div class="accordion-item border-0 border-start border-4 border-warning mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#mysqlMastery">
                                        <i class="fa-solid fa-database me-2 text-warning"></i> 4. MySQL & Database Mastery
                                    </button>
                                </h2>
                                <div id="mysqlMastery" class="accordion-collapse collapse" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Q: How do you solve the N+1 Query Problem?</strong><br>
                                                <span class="text-muted">A: The N+1 problem happens when querying 100 orders and looping them to fetch the user for each, resulting in 101 queries. In CakePHP, I solve this instantly using eager loading: <code>$orders->find()->contain(['Users'])</code>. CakePHP executes exactly 2 queries (one for orders, one <code>WHERE user_id IN (...)</code>) and maps them in memory.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: Explain Database Indexes (B-Tree):</strong><br>
                                                <span class="text-muted">A: An index is like a book's table of contents. Without it, MySQL does a "Full Table Scan" (reading every row). I always add indexes on Foreign Keys (<code>user_id</code>) and heavily searched columns (<code>email</code>, <code>status</code>) to keep queries under 10ms.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: What is a Left Join vs Inner Join?</strong><br>
                                                <span class="text-muted">A: An <em>INNER JOIN</em> only returns rows if there is a match in BOTH tables (Orders with Users). A <em>LEFT JOIN</em> returns ALL rows from the primary table, even if the secondary table has no match (Returns all Orders, User data will be NULL if no user exists).</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: How do you handle race conditions in inventory?</strong><br>
                                                <span class="text-muted">A: If two users buy the last item simultaneously, simple PHP logic fails. I use database-level atomic updates (<code>UPDATE products SET stock = stock - 1 WHERE stock > 0</code>) or Row-Level Locking (Pessimistic Locking / <code>FOR UPDATE</code>) to ensure one transaction waits for the other.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Events & Callbacks -->
                            <div class="accordion-item border-0 border-start border-4 border-success mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#eventsMastery">
                                        <i class="fa-solid fa-bolt me-2 text-success"></i> 5. Event Driven Architecture & Callbacks
                                    </button>
                                </h2>
                                <div id="eventsMastery" class="accordion-collapse collapse" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>beforeSave vs afterSave:</strong><br>
                                                - <code>beforeSave</code>: Best for modifying data *before* it hits the database (e.g., hashing a password, calculating order totals based on items). You can also abort the save here.<br>
                                                - <code>afterSave</code>: Best for triggering secondary actions *after* data is secured (e.g., sending a welcome email, updating elasticsearch, writing to an audit log).
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Global Event Listeners:</strong><br>
                                                Instead of hardcoding logic, CakePHP uses a global Event Manager (<code>EventManager::instance()</code>). When an order is placed, I just dispatch a <code>Model.Order.created</code> event. A completely separate <code>NotificationListener</code> can hear that event and send an email without the OrderModel ever knowing about it. High decoupling!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Large Scale-Up & Multiple DBs -->
                            <div class="accordion-item border-0 border-start border-4 border-dark mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#scalingMastery">
                                        <i class="fa-solid fa-server me-2 text-dark"></i> 6. Large Scale-Up & Multiple Databases
                                    </button>
                                </h2>
                                <div id="scalingMastery" class="accordion-collapse collapse" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <strong>Q: How do you handle multiple databases in CakePHP?</strong><br>
                                                <span class="text-muted">A: CakePHP's <code>config/app_local.php</code> allows defining multiple connection arrays (e.g., <code>'default'</code>, <code>'legacy_erp'</code>). Inside a Table class, I override the <code>defaultConnectionName()</code> method to return <code>'legacy_erp'</code>. Now, this model transparently reads/writes to a completely different database on a different server, while still joining beautifully in the app layer.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: What is Database Read/Write Splitting?</strong><br>
                                                <span class="text-muted">A: When a single database gets overwhelmed by traffic, we scale horizontally. We create 1 Master DB (for INSERT/UPDATE) and 3 Replica DBs (for SELECT). I configure CakePHP to automatically route all <code>find()</code> queries to the Replicas, drastically reducing the load on the Master.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: How do you scale background processing?</strong><br>
                                                <span class="text-muted">A: Never send emails or process PDFs during a web request. I push a payload to the <code>queued_jobs</code> table (or RabbitMQ). A fleet of CLI daemon workers (<code>bin/cake queue run</code>) picks up the jobs asynchronously, allowing the API to respond to the user in 20ms instead of 3000ms.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 7. Advanced Performance & Scenarios -->
                            <div class="accordion-item border-0 border-start border-4 border-primary mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#performanceMastery">
                                        <i class="fa-solid fa-gauge-high me-2 text-primary"></i> 7. Advanced Performance & Scenario Handling
                                    </button>
                                </h2>
                                <div id="performanceMastery" class="accordion-collapse collapse" data-bs-parent="#interviewAccordion">
                                    <div class="accordion-body small bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Q: How does PHP Garbage Collection (GC) work?</strong><br>
                                                <span class="text-muted">A: PHP uses Reference Counting. When a variable hits 0 references, it's freed. The GC specifically looks for "Circular References" (Object A references B, B references A) and cleans them up to prevent memory leaks, which is critical in long-running queue workers.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: How do you process a 10GB CSV without running out of memory?</strong><br>
                                                <span class="text-muted">A: You absolutely cannot use <code>file_get_contents()</code>. I use <strong>Generators</strong> (the <code>yield</code> keyword). I <code>fopen()</code> the file and <code>yield</code> each line one by one using <code>fgetcsv()</code>, keeping memory consumption perfectly flat at ~5MB regardless of file size.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: Explain OPcache vs JIT in PHP 8:</strong><br>
                                                <span class="text-muted">A: <em>OPcache</em> caches the compiled OpCode in memory so PHP doesn't have to re-compile the script on every request. <em>JIT (Just-In-Time)</em> takes it further by compiling parts of the OpCode directly into raw CPU machine code, massively speeding up mathematical/CPU-bound tasks.</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Q: Scenario - The site crashes during a flash sale. What do you do?</strong><br>
                                                <span class="text-muted">A: <br>1. <strong>Triage:</strong> Check monitoring (New Relic) to find the slow query. Use <code>SHOW FULL PROCESSLIST</code> to kill locked queries.<br>2. <strong>Immediate Fix:</strong> Scale horizontally behind the load balancer.<br>3. <strong>Long-term:</strong> Aggressively Cache the catalog in Redis, and offload order placement to a RabbitMQ background queue so the DB doesn't lock during write spikes.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.18.3/swagger-ui-bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.18.3/swagger-ui-standalone-preset.js"></script>
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: true, theme: 'default' });
        
        window.mermaidAPI = mermaid;
    </script>
    
    <script>
        // Swagger UI Init
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/swagger.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
            window.ui = ui;
            
            // Render initial graph
            setTimeout(() => showGraph('catalog'), 500);
        };

        // Graph Definitions
        const graphs = {
            'catalog': {
                title: "Catalog & Inventory Module",
                badge: "Catalog Module",
                desc: "This module is the core of the e-commerce system. It handles nested category trees (using CakePHP's TreeBehavior) and tracks physical product inventory.",
                highlights: `
                    <li><strong>TreeBehavior:</strong> The <code>categories</code> table uses <code>parent_id</code> to create infinite hierarchical categories.</li>
                    <li><strong>CounterCache:</strong> We can use CounterCache on Products to track how many items are in a category automatically.</li>
                    <li><strong>SoftDeletes:</strong> Products are soft-deleted to preserve historical order integrity.</li>
                `,
                graph: `erDiagram
                    PRODUCTS {
                        int id PK
                        string name
                        decimal price
                        int stock
                        int category_id FK
                        int brand_id FK
                    }
                    CATEGORIES {
                        int id PK
                        string name
                        int parent_id FK
                    }
                    BRANDS {
                        int id PK
                        string name
                    }
                    CATEGORIES ||--o{ PRODUCTS : hasMany
                    BRANDS ||--o{ PRODUCTS : hasMany`
            },
            'orders': {
                title: "Order Management & Invoicing",
                badge: "Orders Module",
                desc: "This module handles the entire financial transaction lifecycle. It uses strict relationships to ensure financial compliance and prevent double-billing.",
                highlights: `
                    <li><strong>Strict 1:1 Invoicing:</strong> <code>Orders hasOne Invoices</code> ensures an order can never be billed twice at the DB level.</li>
                    <li><strong>Point-in-Time Pricing:</strong> <code>OrderItems</code> copies the product price at the exact time of checkout to prevent historical corruption.</li>
                    <li><strong>Event-Driven:</strong> Uses <code>afterSave</code> events to deduct stock from the Catalog module.</li>
                `,
                graph: `erDiagram
                    ORDERS {
                        int id PK
                        string order_number
                        string status
                        int user_id FK
                        decimal total
                    }
                    ORDER_ITEMS {
                        int id PK
                        int order_id FK
                        int product_id FK
                        int quantity
                        decimal price
                    }
                    INVOICES {
                        int id PK
                        int order_id FK
                        string status
                    }
                    PAYMENTS {
                        int id PK
                        int order_id FK
                        decimal amount
                        string status
                    }
                    USERS ||--o{ ORDERS : hasMany
                    ORDERS ||--|{ ORDER_ITEMS : hasMany
                    ORDERS ||--o| INVOICES : hasOne
                    ORDERS ||--o{ PAYMENTS : hasMany
                    PRODUCTS ||--o{ ORDER_ITEMS : belongsTo`
            },
            'auth': {
                title: "Authentication & RBAC Security",
                badge: "Security Module",
                desc: "A highly secure Role-Based Access Control (RBAC) system. It manages user identities, permissions, and session tokens.",
                highlights: `
                    <li><strong>belongsToMany (Join Table):</strong> Uses <code>group_users</code> to assign users to multiple permission groups dynamically.</li>
                    <li><strong>JWT Tokens:</strong> <code>api_tokens</code> table stores hashed refresh tokens for stateless API authentication.</li>
                    <li><strong>Password Hashing:</strong> <code>UsersTable::beforeMarshal</code> automatically hashes passwords using Bcrypt.</li>
                `,
                graph: `erDiagram
                    USERS {
                        int id PK
                        string username
                        string password
                        int role_id FK
                    }
                    ROLES {
                        int id PK
                        string name
                    }
                    PERMISSIONS {
                        int id PK
                        string resource
                        string action
                    }
                    GROUPS {
                        int id PK
                        string name
                    }
                    GROUP_USERS {
                        int user_id FK
                        int group_id FK
                    }
                    API_TOKENS {
                        int id PK
                        int user_id FK
                        string token
                    }
                    ROLES ||--o{ USERS : hasMany
                    ROLES }o--o{ PERMISSIONS : belongsToMany
                    USERS }o--o{ GROUPS : belongsToMany
                    USERS ||--o{ API_TOKENS : hasMany`
            },
            'comms': {
                title: "Communications & Activity Logging",
                badge: "System Module",
                desc: "A globally accessible module that handles system-wide operations like Email templates, Contact tracking, and Audit logging.",
                highlights: `
                    <li><strong>Polymorphic Audit Logs:</strong> <code>audit_logs</code> tracks changes across ALL tables without strict foreign keys.</li>
                    <li><strong>Background Jobs:</strong> Queues emails via <code>queued_jobs</code> table to ensure the API responds instantly.</li>
                    <li><strong>Global Event Listeners:</strong> Listens to events from the Orders module to trigger notifications.</li>
                `,
                graph: `erDiagram
                    EMAIL_TEMPLATES {
                        int id PK
                        string name
                        string subject
                        text body
                    }
                    SENT_EMAILS {
                        int id PK
                        int template_id FK
                        string to_email
                        string status
                    }
                    CMS_PAGES {
                        int id PK
                        string slug
                        text content
                    }
                    SETTINGS {
                        int id PK
                        string key
                        string value
                    }
                    AUDIT_LOGS {
                        int id PK
                        string table_name
                        int foreign_key
                        json dirty_fields
                    }
                    EMAIL_TEMPLATES ||--o{ SENT_EMAILS : hasMany`
            }
        };

        async function showGraph(type) {
            const container = document.getElementById('mermaid-container');
            container.innerHTML = ''; 
            
            // Remove active classes
            document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active'));
            // Add active class
            if (event && event.target) {
                event.target.classList.add('active');
            }

            const data = graphs[type];
            
            // Update panel details
            document.getElementById('current-module-badge').innerText = data.badge;
            document.getElementById('module-title').innerText = data.title;
            document.getElementById('module-description').innerText = data.desc;
            document.getElementById('module-highlights').innerHTML = data.highlights;

            try {
                const { svg } = await window.mermaidAPI.render('graphDiv' + Date.now(), data.graph);
                container.innerHTML = svg;
            } catch (error) {
                console.error("Mermaid render error", error);
            }
        }
    </script>
</body>
</html>
