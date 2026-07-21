<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * OrmPlayground command.
 * 
 * A live interactive sandbox for testing ORM queries directly from the CLI.
 */
class OrmPlaygroundCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Live Interactive Sandbox for the CakePHP ORM');
        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('<info>Welcome to the CakePHP ORM Live Sandbox!</info>');
        $io->out('==================================================');
        $io->out('This is your personal playground to interact with the database natively.');
        $io->hr();

        while (true) {
            $io->out("\n<comment>Available Demonstrations:</comment>");
            $io->out('1. Fetch all active Users (Basic Query)');
            $io->out('2. Find a Product with its Category & Brand (Eager Loading / contain)');
            $io->out('3. Fetch Recent Orders with Items (Deep Eager Loading)');
            $io->out('4. Create a new dummy Product (Entity Saving)');
            $io->out('q. Quit');
            
            $choice = $io->ask('Select a demo to run (1-4, q):');
            
            if ($choice === 'q' || $choice === 'quit') {
                $io->out('Exiting Sandbox...');
                break;
            }

            try {
                switch ($choice) {
                    case '1':
                        $this->demoFetchUsers($io);
                        break;
                    case '2':
                        $this->demoFetchProducts($io);
                        break;
                    case '3':
                        $this->demoFetchOrders($io);
                        break;
                    case '4':
                        $this->demoCreateProduct($io);
                        break;
                    default:
                        $io->error('Invalid selection.');
                }
            } catch (\Exception $e) {
                $io->error('Error executing demo: ' . $e->getMessage());
            }
        }

        return static::CODE_SUCCESS;
    }
    
    private function demoFetchUsers(ConsoleIo $io)
    {
        $io->out("\n<info>Executing:</info> \$this->fetchTable('Users')->find()->limit(3)->all();");
        $usersTable = $this->fetchTable('Users');
        $users = $usersTable->find()->limit(3)->all();
        
        foreach ($users as $user) {
            $io->out("- User ID: {$user->id} | Email: {$user->email} | Active: " . ($user->active ? 'Yes' : 'No'));
        }
    }
    
    private function demoFetchProducts(ConsoleIo $io)
    {
        $io->out("\n<info>Executing:</info> \$this->fetchTable('Products')->find()->contain(['Categories', 'Brands'])->first();");
        $productsTable = $this->fetchTable('Products');
        $product = $productsTable->find()->contain(['Categories', 'Brands'])->first();
        
        if ($product) {
            $io->out("Product: {$product->name}");
            $io->out("Category: " . ($product->category ? $product->category->name : 'None'));
            $io->out("Brand: " . ($product->brand ? $product->brand->name : 'None'));
            $io->out("Price: {$product->price}");
        } else {
            $io->out("No products found.");
        }
    }
    
    private function demoFetchOrders(ConsoleIo $io)
    {
        $io->out("\n<info>Executing:</info> \$this->fetchTable('Orders')->find()->contain(['OrderItems.Products'])->orderBy(['Orders.id' => 'DESC'])->first();");
        $ordersTable = $this->fetchTable('Orders');
        $order = $ordersTable->find()
            ->contain(['OrderItems' => ['Products']])
            ->orderBy(['Orders.id' => 'DESC'])
            ->first();
            
        if ($order) {
            $io->out("Order #{$order->order_number} | Status: {$order->status} | Total: {$order->total}");
            $io->out("Items:");
            if (empty($order->order_items)) {
                $io->out("  (No items found)");
            } else {
                foreach ($order->order_items as $item) {
                    $productName = $item->product ? $item->product->name : 'Unknown Product';
                    $io->out("  - {$item->quantity}x {$productName} @ {$item->price}");
                }
            }
        } else {
            $io->out("No orders found.");
        }
    }
    
    private function demoCreateProduct(ConsoleIo $io)
    {
        $io->out("\n<info>Creating a new entity and saving it...</info>");
        $productsTable = $this->fetchTable('Products');
        
        $name = $io->ask('Enter new product name (or leave empty to cancel):');
        if (empty(trim($name))) {
            return;
        }
        
        $price = $io->ask('Enter product price:', '9.99');
        
        $product = $productsTable->newEmptyEntity();
        $product->name = $name;
        $product->price = (float)$price;
        $product->stock = 100;
        $product->sku = 'SKU-' . time();
        $product->is_active = true;
        
        $io->out("\nAttempting to save:");
        if ($productsTable->save($product)) {
            $io->success("Successfully saved new Product! ID: {$product->id}");
        } else {
            $io->error("Failed to save product.");
            $io->out(print_r($product->getErrors(), true));
        }
    }
}
