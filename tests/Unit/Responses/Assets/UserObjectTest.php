<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\JoinedViaStarterPackObject;
use Atproto\Responses\Objects\LabelsObject;
use Atproto\Responses\Objects\UserObject;
use Atproto\Responses\Objects\ViewerObject;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Supports\AssetTest;

class UserObjectTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    private TestUserAsset $userAsset;
    private array $data;

    /**
     * @throws InvalidArgumentException
     */
    protected static function getData(): array
    {
        $vars = self::assetTestGetData();

        $data = [
            'did' => 'did:example:123',
            'handle' => 'example_handle',
            'displayName' => 'Example User',
            'description' => 'This is an example user.',
            'avatar' => 'avatar_url',
            'banner' => 'banner_url',
            'followersCount' => 100,
            'followsCount' => 50,
            'postsCount' => 10,
            'associated' => ['data' => 'example'],
            'joinedViaStarterPack' => ['data' => 'example'],
            'indexedAt' => '2024-01-01T00:00:00Z',
            'createdAt' => '2024-01-01T00:00:00Z',
            'viewer' => ['data' => 'example'],
            'labels' => ['data' => ['example']]
        ];

        $asset = new TestUserAsset($data);

        return array_merge($vars, [
            $data,
            $asset
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        parent::setUp();

        list(
            $this->faker,
            static::$falsyValues,
            $this->data,
            $this->userAsset
        ) = self::getData();
    }

    public function testConstructorSetsContent()
    {
        $this->assertInstanceOf(TestUserAsset::class, $this->userAsset);
    }

    public function testDynamicMethodAccess()
    {
        $this->assertEquals('did:example:123', $this->userAsset->did());
        $this->assertEquals('example_handle', $this->userAsset->handle());
        $this->assertEquals('Example User', $this->userAsset->displayName());
        $this->assertEquals('This is an example user.', $this->userAsset->description());
        $this->assertEquals('avatar_url', $this->userAsset->avatar());
        $this->assertEquals('banner_url', $this->userAsset->banner());
        $this->assertEquals(100, $this->userAsset->followersCount());
        $this->assertEquals(50, $this->userAsset->followsCount());
        $this->assertEquals(10, $this->userAsset->postsCount());

        $this->assertInstanceOf(AssociatedObject::class, $this->userAsset->associated());
        $this->assertInstanceOf(JoinedViaStarterPackObject::class, $this->userAsset->joinedViaStarterPack());
        $this->assertInstanceOf(Carbon::class, $this->userAsset->indexedAt());
        $this->assertInstanceOf(Carbon::class, $this->userAsset->createdAt());
        $this->assertInstanceOf(ViewerObject::class, $this->userAsset->viewer());
        $this->assertInstanceOf(LabelsObject::class, $this->userAsset->labels());
    }

    public function testCastsMethod()
    {
        $reflectionClass = new ReflectionClass($this->userAsset);
        $reflectionMethod = $reflectionClass->getMethod('casts');
        $reflectionMethod->setAccessible(true);
        $casts = $reflectionMethod->invoke($this->userAsset);

        $this->assertArrayHasKey('associated', $casts);
        $this->assertArrayHasKey('indexedAt', $casts);
        $this->assertArrayHasKey('joinedViaStarterPack', $casts);
        $this->assertArrayHasKey('createdAt', $casts);
        $this->assertArrayHasKey('viewer', $casts);
        $this->assertArrayHasKey('labels', $casts);

        $this->assertEquals(AssociatedObject::class, $casts['associated']);
        $this->assertEquals(DatetimeObject::class, $casts['indexedAt']);
        $this->assertEquals(JoinedViaStarterPackObject::class, $casts['joinedViaStarterPack']);
        $this->assertEquals(DatetimeObject::class, $casts['createdAt']);
        $this->assertEquals(ViewerObject::class, $casts['viewer']);
        $this->assertEquals(LabelsObject::class, $casts['labels']);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): TestUserAsset
    {
        return new TestUserAsset($data);
    }
}

class TestUserAsset
{
    use UserObject;
}
