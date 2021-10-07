<?php

namespace App\Console\Commands;

use App\Helpers\Dates\DateHelper;
use App\Models\Articles\Article;
use App\Models\Articles\ArticleType;
use App\Models\Budgets\Expense;
use App\Models\Clients\Client;
use App\Models\Contracts\Contract;
use App\Models\Contracts\ContractStates;
use App\Models\Contracts\Extension;
use App\Models\Sales\Invoice;
use App\Models\Sales\Product;
use App\Models\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MigrateFromOldSicv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sicv:migrate {old_db} {--host=} {--pass=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates the data from the old SICV application';

    protected $connection;

    protected $userAssociations = [];
    protected $clientAssociations = [];
    protected $productAssociations = [];
    protected $adminId = 1;

    protected $articleIdsAssociatons = [
        '1' => '1',
        '2' => ArticleType::GOLD_ID,
        '3' => '6',
        '4' => '14',
        '5' => '16',
        '6' => '17',
        '7' => '19',
        '8' => '20',
        '9' => '18',
        '10' => '21',
        '11' => '22',
        '12' => '24',
        '13' => '25',
        '14' => '26',
        '15' => '27',
    ];

    protected $contractStatesAssociations = [
        '1' => ContractStates::ACTIVE,
        '2' => ContractStates::TERMINATED,
        '3' => ContractStates::ENDED,
        '4' => ContractStates::ENDED,
        '5' => ContractStates::ENDED,
        '6' => ContractStates::LEGALPROBLEM,
        '7' => ContractStates::ANNULLED,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createDBConfig();
        echo "Creating the new connection to the old database...\n";
        $this->connection = DB::connection('olddb');

        try {
            DB::beginTransaction();
            $this->connection->statement('set names utf8mb4');

            echo "Migrating the users...\n";
            $this->createUsersWithAssociations();
            echo "Migrating the clients...\n";
            $this->migrateClientsWithAssociation();
            echo "Migrating the contracts...\n";
            $this->migrateContracts();
            echo "Migrating the extensions...\n";
            $this->migrateExtensions();
            echo "Migrating the expenses...\n";
            $this->migrateExpenses();
            echo "Migrating the products...\n";
            $this->migrateProducts();
            echo "Migrating the invoices...\n";
            $this->migrateInvoices();
            echo "Migrating the invoices products...\n";
            $this->migrateInvoiceProducts();

            DB::commit();
            echo "PROCESS TERMINATED\nPlease check for annulations\n";
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createDBConfig()
    {
        // Create the old db connection
        Config::set(
            'database.connections.olddb',
            [
                'driver' => 'mysql',
                'host' => $this->option('host') ?? '127.0.0.1',
                'database' => $this->argument('old_db'),
                'username' => 'root',
                'password' => $this->option('pass'),
                'collation' => 'utf8_general_ci',
                'charset' => 'utf8',
            ]
        );
    }

    public function createUsersWithAssociations(): void
    {
        echo "\tObtaining the users from the old database...\n";
        $users = $this->connection->table('usuario')->get();
        echo "\tPerforming migrations\n";
        foreach ($users as $user) {
            if ($user->idusuario == 'admin' && $admin = User::where('username', 'admin')->first()) {
                $this->adminId = $admin->id();
                $this->userAssociations[$user->idusuario] = $admin->id();
                continue;
            }
            $newUser = (new User(
                [
                    'username' => $user->idusuario,
                    'name' => $user->nombre,
                    'email' => is_null($user->email) ? $user->idusuario . '@temp.com' : $user->email,
                    'active' => 1,
                    'role' => 100,
                ]
            ))->setPassword('temporal');
            $newUser->save();
            $this->userAssociations[$user->idusuario] = $newUser->id();
            echo "{$user->idusuario} ";
        }
        echo "\n";
    }

    public function migrateClientsWithAssociation()
    {
        echo "\tObtaining the clients from the old database...\n";
        $clients = $this->connection->table('cliente')->get();
        echo "\tPerforming migrations\n";
        foreach ($clients as $client) {
            // Associate the client old_id with the new id
            $newClient = Client::create(
                [
                    'name' => ucwords(mb_strtolower($client->nombre)),
                    'id_type' => 'CC',
                    'id_number' => $client->idcliente,
                    'id_expedition' => ucwords(mb_strtolower($client->lugarexpedicion)),
                    'address' => $client->direccion,
                    'phone_number' => $client->telefono,
                    'cell_number' => $client->celular,
                    'city' => $client->ciudad,
                ]
            );
            $this->clientAssociations[$client->idcliente] = $newClient->id();
            echo "{$client->idcliente} ";
        }
        echo "\n";
    }

    public function migrateContracts()
    {
        echo "\tObtaining the contracts from the old database...\n";
        $contracts = $this->connection->table('contrato')->get();
        echo "\tPerforming migrations\n";
        foreach ($contracts as $contract) {
            $newContract = (new Contract(
                [
                    'user_id' => $this->getNewUserId($contract->usuario),
                    'client_id' => $this->getNewClientId($contract->cliente),
                    'months' => $contract->nromeses,
                    'percentage' => $contract->porcentaje,
                    'amount' => $contract->valor,
                    'state' => $this->getNewContractState($contract->estado),
                    'created_at' => $contract->fechaingreso,
                    'end_date' => $contract->fechasalida,
                    'end_amount' => $contract->valorcancelado,
                ]
            )
            )->setId($contract->idcontrato);
            $newContract->save();
            // Creo el nuevo articulo
            $article = Article::create(
                [
                    'description' => $contract->articulo,
                    'weight' => $contract->peso,
                    'article_type_id' => $this->getNewArticleType($contract->tipoarticulo),
                ]
            );
            // Guardo la relacion
            $newContract->articles()->attach($article, ['article_amount' => $contract->valor]);
            echo "{$newContract->id()} ";
        }
        echo "\n";
    }

    public function getNewArticleType($old_article_type)
    {
        return $this->articleIdsAssociatons[$old_article_type];
    }

    public function getNewClientId($old_client_id)
    {
        return $this->clientAssociations[$old_client_id] ?? Client::where('id_number', $old_client_id)->first()->id();
    }

    public function getNewUserId($old_user_id)
    {
        return $this->userAssociations[$old_user_id] ?? $this->adminId;
    }

    public function getNewProductId($old_product_id)
    {
        return $this->productAssociations[$old_product_id];
    }

    public function getNewContractState($old_contract_state)
    {
        return $this->contractStatesAssociations[$old_contract_state];
    }

    public function migrateExtensions()
    {
        echo "\tObtaining the extensions from the old database...\n";
        $extensions = $this->connection->table('prorroga')->get();
        echo "\tPerforming migrations\n";
        foreach ($extensions as $extension) {
            $newExtension = Extension::create(
                [
                    'amount' => $extension->valor,
                    'contract_id' => $extension->contrato,
                    'user_id' => $this->getNewUserId($extension->usuario),
                    'created_at' => $extension->fecha . ' ' . $extension->hora,
                ]
            );
            echo "{$newExtension->id()} ";
        }
        echo "\n";
    }

    public function migrateExpenses()
    {
        echo "\tObtaining the expenses from the old database...\n";
        $expenses = $this->connection->table('gasto')->get();
        echo "\tPerforming migrations\n";
        foreach ($expenses as $expense) {
            $newExpense = Expense::create(
                [
                    'amount' => $expense->valor,
                    'created_at' => $expense->fecha,
                    'description' => $expense->concepto,
                    'expense_type_id' => $expense->tipogasto,
                    'user_id' => $this->getNewUserId($expense->usuario),
                ]
            );
            echo "{$newExpense->id()} ";
        }
        echo "\n";
    }

    public function migrateProducts()
    {
        echo "\tObtaining the products from the old database...\n";
        $products = $this->connection->table('articulo')->get();
        echo "\tPerforming migrations\n";
        foreach ($products as $product) {
            // I need to find the id of the article from the contract
            if (is_null($product->contrato)) {
                $article = Article::create([
                    'description' => $product->articulo,
                    'article_type_id' => $this->getNewArticleType($product->tipoarticulo),
                ]);
                $article_id = $article->id();
                $contract_id = null;
            } else {
                // The article is in the articles
                $article = Contract::find($product->contrato)->articles->first();
                $article_id = $article->id();
                $contract_id = $product->contrato;
            }
            $newProduct = Product::create([
                'buy_price' => $product->valorcompra,
                'sell_price' => $product->valorventa,
                'article_id' => $article_id,
                'contract_id' => $contract_id,
                'quantity' => $product->disponible,
            ]);
            // Creo las nuevas asociaciones de producto
            $this->productAssociations[$product->idarticulo] = $newProduct->id();
            echo $product->idarticulo . ' ';
        }
        echo "\n";
    }

    public function migrateInvoices()
    {
        echo "\tObtaining the invoices from the old database...\n";
        $invoices = $this->connection->table('notacobro')->get();
        echo "\tPerforming migrations\n";
        foreach ($invoices as $invoice) {
            $newInvoice = (new Invoice([
                'created_at' => DateHelper::create($invoice->fecha)->toSQLTimestamp(),
                'client_id' => $this->getNewClientId($invoice->cliente),
                'amount' => (is_null($invoice->total) ? 0 : $invoice->total),
                'user_id' => $this->getNewUserId($invoice->usuario),
            ]))->setId($invoice->idnotacobro);
            $newInvoice->save();
            echo $newInvoice->id() . ' ';
        }
        echo "\n";
    }

    public function migrateInvoiceProducts()
    {
        echo "\tObtaining the invoice products from the old database...\n";
        $invoiceProducts = $this->connection->table('detalle')->get();
        echo "\tPerforming migrations\n";
        foreach ($invoiceProducts as $invoiceProduct) {
            $invoice = Invoice::find($invoiceProduct->notacobro);
            $invoice->products()->attach($this->getNewProductId($invoiceProduct->articulo), ['amount' => $invoiceProduct->valor]);
            echo $invoice->id() . '-' . $invoiceProduct->articulo . ' ';
        }
        echo "\n";
    }
}
