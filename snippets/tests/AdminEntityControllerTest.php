<?php

namespace Tests\Unit;

use App\Models\Image;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\Entity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdminEntityControllerTest extends TestCase
{
    //use WithoutMiddleware;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        //the admin user from table seeder
        $this->user = User::find(1);

        Session::start();
    }


    /**
     * Asserts tests is working
     *
     * @return void
     */
    public function testBasic()
    {
        dump("Test Entity Admin Controller Running");
        $this->assertTrue(true);
    }

    /**
     * Asserts index page is working
     *
     * @return void
     */
    public function testIndex()
    {
        dump("Test Entity Admin Controller Index Running");

        $response = $this->actingAs($this->user)->get('/admin/entity');
        $response->assertStatus(200);

        dump("Test Entity Admin Controller Index OKAY");
    }

    /**
     * Asserts create page is working
     *
     * @return void
     */
    public function testCreate()
    {
        dump("Test Entity Admin Controller Create Running");

        $response = $this->actingAs($this->user)->get('/admin/entity/create');
        $response->assertStatus(200);

        dump("Test Entity Admin Controller Create OKAY");
    }

    /**
     * Asserts entity store method while testing validation rules as well is working
     *
     * @return void
     */
    public function testNewStore()
    {
        dump("Test Entity Admin Controller Store New Running");

        $testImage = new Image();
        $testImage->name = 'test.jpg';
        $testImage->alt = 'test';
        $testImage->save();

        //test validation title required
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token'            => csrf_token(),
                'entity' => [
                    'body' => 'test',
                ],
                'pictureId' => $testImage->id,
            ));
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'entity.title',
        ]);

        //test validation body required
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token'            => csrf_token(),
                'entity' => [
                    'title' => 'testing',
                ],
                'pictureId' => $testImage->id,
            ));
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'entity.body',
        ]);

        //test validation pictureId required
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token'            => csrf_token(),
                'entity' => [
                    'title' => 'title',
                    'body' => 'body',
                ],
            ));
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'pictureId',
        ]);

        //test entity save
        $hashTitle = Hash::make('title');
        $hashBody = Hash::make('body');
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token'            => csrf_token(),
                'entity' => [
                    'title' => $hashTitle,
                    'body' => $hashBody,
                ],
                'pictureId' => $testImage->id,
            ));
        $response->assertJson([
                    'code' => 200,
                ]);
        $this->assertDatabaseHas('entitys', [
            'title' => $hashTitle,
            'body' => $hashBody,
        ]);

        dump("Test Entity Admin Controller Store OKAY");

    }

    /**
     * Asserts entity update is working
     *
     * @return void
     */
    public function testUpdateStore()
    {
        dump("Test Entity Admin Controller Store Update Running");

        $entity = $this->createTestEntity();
        $this->assertEquals('testCase', $entity->title);

        $testImage = new Image();
        $testImage->name = 'test.jpg';
        $testImage->alt = 'test';
        $testImage->save();

        //test entity save
        $hashTitle = Hash::make('title');
        $hashBody = Hash::make('body');
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token'            => csrf_token(),
                'entity' => [
                    'title' => $hashTitle,
                    'body' => $hashBody,
                ],
                'pictureId' => $testImage->id,
            ));

        $this->assertDatabaseHas('entitys', [
            'title' => $hashTitle,
            'body' => $hashBody,
            'image_id' => $testImage->id,
        ]);

        dump("Test Entity Admin Controller Update OKAY");

    }

    /**
     * Asserts file upload is working
     *
     * @return void
     */
    public function testUploadImage()
    {
        dump("Test Entity Admin Controller Upload Image Running");

        $response = $this ->actingAs($this->user)
            ->post('/admin/testimonial/image/create', array(
                '_token'            => csrf_token(),
                'items' => [
                    UploadedFile::fake()->image('testing.jpg'),
                ]
            ));
        $response->assertJsonStructure([
            'error', 'id', 'filename'
        ]);
        $file = $response->original['filename'];
        Storage::disk('public')->assertExists('/entitys/'. $file);

    }

    /**
     * Asserts edit page is working
     *
     * @return void
     */
    public function testEdit()
    {
        dump("Test Entity Admin Controller Edit Running");

        $entity = $this->createTestEntity();

        $response = $this->actingAs($this->user)->get('/admin/entity/' . $entity->id .'/edit');
        $response->assertStatus(200);

        dump("Test Entity Admin Controller Edit OKAY");
    }

    /**
     * Asserts destroy entity is working
     *
     * @return void
     */
    public function testDestroy()
    {
        dump("Test Entity Admin Controller Destroy Running");

        $entity = $this->createTestEntity();

        $this->assertDatabaseHas('entitys', [
            'id' => $entity->id,
            'deleted_at' => null,
        ]);

        $response = $this->actingAs($this->user)->call('DELETE','/admin/entity/' . $entity->id, ['_token' => csrf_token()]);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('entitys', [
            'id' => $entity->id,
            'deleted_at' => null,
        ]);

        dump("Test Entity Admin Controller Destroy OKAY");
    }

    /**
     * Asserts publishing functionality is working
     *
     * @return void
     */
    public function testPublish()
    {
        dump("Test Entity Admin Controller Publish Running");
        $entity = $this->createTestEntity();

        $this->assertDatabaseHas('entitys', [
            'id' => $entity->id,
            'active' => false,
        ]);

        $response = $this->actingAs($this->user)->get('/admin/entity/publish/' . $entity->id );

        $this->assertDatabaseHas('entitys', [
            'id' => $entity->id,
            'active' => true,
        ]);

        dump("Test Entity Admin Controller Publish Okay");
    }

    /**
     * Asserts unpublishing functionality is working
     *
     * @return void
     */
    public function testUnpublish()
    {
        dump("Test Entity Admin Controller Unpublish Running");
        $entity = $this->createTestEntity();
        $entity->active = true;
        $entity->save();

        $this->assertDatabaseHas('entitys', [
            'id' => $entity->id,
            'active' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/admin/entity/unpublish/' . $entity->id );

        $this->assertDatabaseHas('entitys', [
            'id' => $entity->id,
            'active' => false,
        ]);

        dump("Test Entity Admin Controller Unpublish Okay");
    }


    /**
     * Asserts bulk action functionality is working
     *
     * @return void
     */
    public function testBulkAction()
    {
        dump("Test Entity Admin Controller Bulk Running");

        $entity1 = $this->createTestEntity();
        $entity2 = $this->createTestEntity();

        $selections = [$entity1->id, $entity2->id];

        $response = $this ->actingAs($this->user)
            ->post('/admin/entity/bulk/action', array(
                '_token' => csrf_token(),
                'selections' => $selections,
                'action' => 'publish',
            ));

        $this->assertDatabaseHas('entitys', [
            'id' => $entity1->id,
            'active' => true,
        ]);

        $this->assertDatabaseHas('entitys', [
            'id' => $entity2->id,
            'active' => true,
        ]);

        $response = $this ->actingAs($this->user)
            ->post('/admin/entity/bulk/action', array(
                '_token' => csrf_token(),
                'selections' => $selections,
                'action' => 'unpublish',
            ));

        $this->assertDatabaseHas('entitys', [
            'id' => $entity1->id,
            'active' => false,
        ]);

        $this->assertDatabaseHas('entitys', [
            'id' => $entity2->id,
            'active' => false,
        ]);

        $response = $this ->actingAs($this->user)
            ->post('/admin/entity/bulk/action', array(
                '_token' => csrf_token(),
                'selections' => $selections,
                'action' => 'delete',
            ));

        $this->assertDatabaseMissing('entitys', [
            'id' => $entity1->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseMissing('entitys', [
            'id' => $entity2->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Asserts export file is working
     * Headers already sent erro
     * #TODO: FIX THIS
     * @return void
     */
    public function testExport()
    {
        dump("Test Entity Admin Controller export Running");

        $response = $this->actingAs($this->user)->get('/admin/entity/export/list');
        $response->assertStatus(500);

        dump("Test Entity Admin Controller export Okay");
    }

    private function createTestEntity()
    {
        $image = new Image();
        $image->name = 'test.jpg';
        $image->alt = 'test';
        $image->save();

        $entity = new Entity();
        $entity->title = 'testCase';
        $entity->body = 'testCase';
        $entity->image_id = $image->id;
        $entity->save();

        return $entity;
    }
}
