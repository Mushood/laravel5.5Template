<?php

namespace Tests\Unit;

use App\Models\Image;
use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Entity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminEntityControllerTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

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

        $response = $this->get('/admin/entity');
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

        $response = $this->get('/admin/entity/create');
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

        //test validation csrf token
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(

            ));
        $response->assertStatus(500);

        //test validation title required
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token' => csrf_token(),
                'entity.body' => 'test',
                'pictureId' => 1,
            ));
        $response->assertStatus(500);

        //test validation body required
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token' => csrf_token(),
                'entity.title' => 'test',
                'pictureId' => 1,
            ));
        $response->assertStatus(500);

        //test validation pictureId required
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token' => csrf_token(),
                'entity.title' => 'test',
                'entity.body' => 'test',
            ));
        $response->assertStatus(500);

        //test entity save
        $hashTitle = Hash::make('title');
        $hashBody = Hash::make('body');
        $response = $this ->actingAs($this->user)
            ->post('/admin/entity', array(
                '_token' => csrf_token(),
                'entity.title' => $hashTitle,
                'entity.body' => $hashBody,
                'pictureId' => 1,
            ));
        $response->assertStatus(200)
                ->assertJson([
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
                '_token' => csrf_token(),
                'entity.id' => $entity->id,
                'entity.title' => $hashTitle,
                'entity.body' => $hashBody,
                'pictureId' => $testImage->id,
            ));

        $this->assertDatabaseHas('entitys', [
            'title' => $hashTitle,
            'body' => $hashBody,
        ]);

        $updatedEntity = Entity::find($entity->id);

        $this->assertEquals($updatedEntity->title, $hashTitle);
        $this->assertEquals($updatedEntity->body, $hashBody);
        $this->assertEquals($updatedEntity->image_id, $testImage->id);

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
        Storage::fake('avatars');

        $response = $this->json('POST', '/admin/entity/image/create', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        // Assert the file was stored...
        Storage::disk('avatars')->assertExists('avatar.jpg');

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

        $response = $this->get('/admin/entity/' . $entity->id .'/edit');
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

        $response = $this->delete('/admin/entity/' . $entity->id );

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

        $response = $this->get('/admin/entity/publish/' . $entity->id );

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

        $response = $this->get('/admin/entity/unpublish/' . $entity->id );

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
        ]);

        $this->assertDatabaseMissing('entitys', [
            'id' => $entity2->id,
        ]);
    }

    /**
     * Asserts export file is working
     *
     * @return void
     */
    public function testExport()
    {
        dump("Test Entity Admin Controller export Running");

        $response = $this->get('/admin/entity/export/list');
        $response->assertStatus(200);

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
        $entity->description = 'testCase';
        $entity->image_id = $image->id;
        $entity->active = false;
        $entity->save();

        return $entity;
    }
}
