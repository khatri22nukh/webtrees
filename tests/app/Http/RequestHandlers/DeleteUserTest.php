<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees\Http\RequestHandlers;

use Fisharebest\Webtrees\Services\UserService;
use Fisharebest\Webtrees\TestCase;
use Fisharebest\Webtrees\User;

/**
 * @covers \Fisharebest\Webtrees\Http\RequestHandlers\DeleteUser
 */
class DeleteUserTest extends TestCase
{
    protected static $uses_database = true;

    /**
     * @return void
     */
    public function testDeleteUser(): void
    {
        $user = $this->createMock(User::class);
        $user->method('id')->willReturn(1);

        $user_service = $this->createMock(UserService::class);
        $user_service->expects($this->once())->method('find')->willReturn($user);

        $request  = self::createRequest(RequestMethodInterface::METHOD_POST, [], ['user_id' => $user->id()]);
        $handler  = new DeleteUser($user_service);
        $response = $handler->handle($request);

        self::assertSame(self::STATUS_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage User ID 98765 not found
     * @return void
     */
    public function testDeleteNonExistingUser(): void
    {
        $user_service = $this->createMock(UserService::class);
        $user_service->expects($this->once())->method('find')->willReturn(null);

        $request  = self::createRequest(RequestMethodInterface::METHOD_POST, [], ['user_id' => 98765]);
        $handler  = new DeleteUser($user_service);
        $response = $handler->handle($request);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @expectedExceptionMessage Cannot delete an administrator
     * @return void
     */
    public function testCannotDeleteAdministrator(): void
    {
        $user = $this->createMock(User::class);
        $user->method('id')->willReturn(1);
        $user->expects($this->once())->method('getPreference')->with('canadmin')->willReturn('1');

        $user_service = $this->createMock(UserService::class);
        $user_service->expects($this->once())->method('find')->willReturn($user);

        $request = self::createRequest(RequestMethodInterface::METHOD_POST, [], ['user_id' => $user->id()]);
        $handler = new DeleteUser($user_service);
        $handler->handle($request);
    }
}
