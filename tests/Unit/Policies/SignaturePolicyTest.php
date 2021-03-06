<?php

declare(strict_types=1);

namespace Tipoff\Waivers\Tests\Unit\Policies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Support\Contracts\Models\UserInterface;
use Tipoff\TestSupport\Models\User;
use Tipoff\Waivers\Models\Signature;
use Tipoff\Waivers\Tests\TestCase;

class SignaturePolicyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function view_any()
    {
        /** @var User $authorizedUser */
        $authorizedUser = self::createPermissionedUser('view signatures', true);

        /** @var User $unauthorizedUser */
        $unauthorizedUser = self::createPermissionedUser('view signatures', false);

        $this->assertTrue($authorizedUser->can('viewAny', Signature::class));
        $this->assertFalse($unauthorizedUser->can('viewAny', Signature::class));
    }

    public function data_provider_for_all_permissions_as_creator()
    {
        return [
            'view-true' => ['view', self::createPermissionedUser('view signatures', true), true],
            'view-false' => ['view', self::createPermissionedUser('view signatures', false), false],
            'create-false' => ['create', self::createPermissionedUser('create signatures', false), false],
            'update-false' => ['update', self::createPermissionedUser('update signatures', false), false],
            'delete-true' => ['delete', self::createPermissionedUser('delete signatures', true), false],
            'delete-false' => ['delete', self::createPermissionedUser('delete signatures', false), false],
        ];
    }

    /**
     * @test
     * @dataProvider data_provider_for_all_permissions_not_creator
     * @param string $permission
     * @param UserInterface $user
     * @param bool $expected
     */
    public function all_permissions_not_creator(string $permission, UserInterface $user, bool $expected)
    {
        $signatures = Signature::factory()->make();

        $this->assertEquals($expected, $user->can($permission, $signatures));
    }

    public function data_provider_for_all_permissions_not_creator()
    {
        // Permissions are identical for creator or others
        return $this->data_provider_for_all_permissions_as_creator();
    }
}
