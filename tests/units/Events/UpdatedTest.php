<?php

namespace LaravelEnso\ActivityLog\Test\units\Events;

use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use LaravelEnso\ActivityLog\Contracts\Loggable;
use LaravelEnso\ActivityLog\Events\Updated;
use LaravelEnso\ActivityLog\Facades\Logger;
use LaravelEnso\Core\Models\User;
use LaravelEnso\Enums\Services\Enum;
use LaravelEnso\People\Models\Person;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    private Faker $faker;
    private LoggerTestModel $testModel;
    private LoggerRelatedModel $relationalModel;
    private User $user;
    private array $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = FakerFactory::create();

        LoggerTestModel::createTable();
        LoggerRelatedModel::createTable();

        $this->testModel = $this->createTestModel();
        $this->relationalModel = $this->createRelatedModel();
        $this->testModel->relationalModel()
            ->associate($this->relationalModel)
            ->save();

        $this->user = User::factory()->create();
        $this->user->person()->associate(Person::factory()->create());
        Auth::setUser($this->user);
    }

    /** @test */
    public function can_get_message_with_no_attribute()
    {
        $this->attributes = [];

        $this->register();

        $this->assertEquals(
            ':user updated :model :label',
            (new Updated($this->testModel))->message()
        );
    }

    /** @test */
    public function can_get_message_with_attributes()
    {
        $this->attributes = ['name'];
        $this->register();

        $this->testModel->name = 'test';

        $this->assertEquals(
            [':user updated :model :label', 'with the following changes:', ':attribute0 was changed from :from0 to :to0'],
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
            'attribute0' => 'name',
            'from0' => $oldName,
            'to0' => 'test',
        ], $this->handle());
    }

    /** @test */
    public function can_change_relation_attribute()
    {
        $this->attributes = ['relational_model_id' => [LoggerRelatedModel::class => 'name']];
        $this->register();

        $oldName = $this->testModel->relationalModel->name;
        $this->testModel->relationalModel()
            ->associate($this->createRelatedModel());

        $this->assertEquals([
            'attribute0' => 'relational model',
            'from0' => $oldName,
            'to0' => $this->testModel->relationalModel->name,
        ], $this->handle());
    }

    /** @test */
    public function can_get_new_relation_attribute()
    {
        $this->attributes = ['relational_model_id' => [LoggerRelatedModel::class => 'name']];

        $this->register();

        $this->testModel->relationalModel()
            ->dissociate()->save();

        $this->testModel->relationalModel()
            ->associate($this->createRelatedModel());

        $this->assertEquals([
            'attribute0' => 'relational model',
            'from0' => null,
            'to0' => $this->testModel->relationalModel->name,
        ], $this->handle());
    }

    /** @test */
    public function can_get_delete_relation_attribute()
    {
        $this->attributes = ['relational_model_id' => [LoggerRelatedModel::class => 'name']];

        $this->register();

        $this->testModel->relationalModel()->dissociate();

        $this->assertEquals([
            'attribute0' => 'relational model',
            'from0' => $this->relationalModel->name,
            'to0' => null,
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
            'attribute0' => 'type',
            'from0' => 'Active',
            'to0' => 'Deactive',
        ], $this->handle());
    }

    private function register()
    {
        Logger::register([LoggerTestModel::class => [
            'attributes' => $this->attributes,
        ]]);
    }

    private function createTestModel()
    {
        $test = new LoggerTestModel();
        $test->name = $this->faker->name;
        $test->save();

        return $test;
    }

    private function createRelatedModel()
    {
        $test = new LoggerRelatedModel();
        $test->name = $this->faker->name;
        $test->save();

        return $test;
    }

    private function handle(): array
    {
        return (new Updated($this->testModel))->attributes();
    }
}

class LoggerTestModel extends Model
{
    protected $fillable = ['name', 'type'];

    public function relationalModel()
    {
        return $this->belongsTo(LoggerRelatedModel::class);
    }

    public static function createTable()
    {
        Schema::create('logger_test_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('relational_model_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('name');
            $table->timestamps();
        });
    }
}

class LoggerRelatedModel extends Model
{
    protected $fillable = ['name'];

    public static function createTable()
    {
        Schema::create('logger_related_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}

class Type extends Enum
{
    public const Active = 1;
    public const Deactive = 0;
}

class LoggableStub implements Loggable
{
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
