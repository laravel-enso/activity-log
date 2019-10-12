<?php

namespace LaravelEnso\ActivityLog\Test\units\Events;

use Tests\TestCase;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Enums\app\Services\Enum;
use Illuminate\Database\Schema\Blueprint;
use LaravelEnso\People\app\Models\Person;
use LaravelEnso\ActivityLog\App\Events\Updated;
use LaravelEnso\ActivityLog\app\Facades\Logger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    private $faker;
    private $testModel;
    private $relationalModel;
    private $user;
    private $attributes;

    protected function setUp() :void
    {
        parent::setUp();

        $this->faker = FakerFactory::create();

        TestModel::createTable();
        RelationalModel::createTable();

        $this->testModel = $this->createTestModel();
        $this->relationalModel = $this->createRelationalModel();
        $this->testModel->relationalModel()
            ->associate($this->relationalModel)
            ->save();

        $this->user = factory(User::class)->create();
        $this->user->person()->associate(factory(Person::class)->create());
        Auth::setUser($this->user);

    }

    /** @test */
    public function can_get_message_with_no_attribute()
    {
        $this->register();

        $this->assertEquals(':user updated :model :label',
            (new Updated($this->testModel))->message());
    }

    /** @test */
    public function can_get_message_with_attributes()
    {
        $this->attributes = ['name'];
        $this->register();

        $this->testModel->name = 'test';

        $this->assertEquals(
            [':user updated :model :label', 'with the following changes:', ':attribute1 was changed from :from1 to :to1'],
            (new Updated($this->testModel))->message()
        );
    }

    /** @test */
    public function can_get_attributes()
    {
        $this->attributes = ['name'];
        $this->register();

        $oldName = $this->testModel->name;
        $this->testModel->name = 'test';

        $this->assertEquals([
            'attribute1' => 'name',
            'from1' => $oldName,
            'to1' => 'test',
        ],$this->handle());
    }

    /** @test */
    public function can_change_relation_attribute()
    {
        $this->attributes = ['relational_model_id' => [RelationalModel::class => 'name']];
        $this->register();

        $oldName = $this->testModel->relationalModel->name;
        $this->testModel->relationalModel()
            ->associate($this->createRelationalModel());

        $this->assertEquals([
            'attribute1' => 'relational model',
            'from1' => $oldName,
            'to1' => $this->testModel->relationalModel->name,
        ], $this->handle());
    }


    /** @test */
    public function can_get_new_relation_attribute()
    {
        $this->attributes = ['relational_model_id' => [RelationalModel::class => 'name']];

        $this->register();

        $this->testModel->relationalModel()
            ->dissociate()->save();

        $this->testModel->relationalModel()
            ->associate($this->createRelationalModel());

        $this->assertEquals([
            'attribute1' => 'relational model',
            'from1' => null,
            'to1' => $this->testModel->relationalModel->name,
        ], $this->handle());
    }

    /** @test */
    public function can_get_delete_relation_attribute()
    {
        $this->attributes = ['relational_model_id' => [RelationalModel::class => 'name']];

        $this->register();

        $this->testModel->relationalModel()->dissociate();

        $this->assertEquals([
            'attribute1' => 'relational model',
            'from1' => $this->relationalModel->name,
            'to1' => null,
        ], $this->handle());
    }

    /** @test */
    public function can_get_enum_attributes()
    {
        $this->attributes = ['type' => Type::class];

        $this->register();

        $this->testModel->update(['type' => Type::Active]);
        $this->testModel->type = Type::Deactive;


        $this->assertEquals([
            'attribute1' => 'type',
            'from1' => 'Active',
            'to1' => 'Deactive',
        ], $this->handle());
    }

    private function register()
    {
        Logger::register([TestModel::class => [
            'attributes' => $this->attributes
        ]]);
    }

    private function createTestModel()
    {
        $test = new TestModel();
        $test->name = $this->faker->name;
        $test->save();

        return $test;
    }

    private function createRelationalModel()
    {
        $test = new RelationalModel();
        $test->name = $this->faker->name;
        $test->save();

        return $test;
    }

    private function handle(): array
    {
        return (new Updated($this->testModel))->attributes();
    }
}

class TestModel extends Model {
    protected $fillable = ['name','type'];

    public function relationalModel()
    {
        return $this->belongsTo(RelationalModel::class);
    }

    public static function createTable()
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('relational_model_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('name');
            $table->timestamps();
        });
    }
}

class RelationalModel extends Model {
    protected $fillable = ['name'];

    public static function createTable()
    {
        Schema::create('relational_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}

class Type extends Enum {
    public const Active = 1;
    public const Deactive = 0;
}

class LoggableStub implements Loggable {
    private $testModel;

    public function __construct($testModel)
    {
        $this->testModel = $testModel;
    }

    public function type(): int
    {
        return 0;
    }

    public function model(): Model
    {
        return $this->testModel;
    }

    public function message(): string
    {
        return 'message';
    }

    public function icon(): string
    {
        return 'icon';
    }
    
    public function iconClass(): string
    {
        return 'is-info';
    }
}
