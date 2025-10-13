<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FriendshipService;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class FriendshipTest extends TestCase
{
    use WithFaker;

    protected MockInterface $userMock;
    protected User $sender;
    protected FriendshipService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userMock = Mockery::mock('alias:App\Models\User');

        $this->sender = new User();
        $this->sender->id = 1;
        $this->sender->email_verified_at = now();

        $this->service = new FriendshipService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_send_request_returns_404_when_receiver_not_found(): void
    {
        $this->userMock->shouldReceive('getById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $response = $this->service->sendRequest($this->sender, 999);

        $this->assertEquals(404, $response->status());
        $this->assertEquals(
            ['message' => 'The user you are trying to add does not exist.'],
            $response->getData(true)
        );
    }

    public function test_send_request_returns_400_when_receiver_not_verified(): void
    {
        $this->userMock->shouldReceive('getById')
            ->once()
            ->with(5)
            ->andReturn((object)[
                'id' => 5,
                'email_verified_at' => null,
            ]);

        $response = $this->service->sendRequest($this->sender, 5);

        $this->assertEquals(400, $response->status());
        $this->assertEquals(
            ['message' => 'The user is not active and cannot be added as a friend.'],
            $response->getData(true)
        );
    }

     public function test_send_request_returns_400_when_request_already_exists(): void
     {
         $receiverId = 2;

         $this->userMock->shouldReceive('getById')
             ->once()
             ->with($receiverId)
             ->andReturn((object)[
                 'id' => $receiverId,
                 'email_verified_at' => now(),
             ]);

         $friendshipMock = Mockery::mock('alias:App\Models\Friendship');
         $friendshipMock->shouldReceive('getFriendRequestBetweenUsers')
             ->once()
             ->with($this->sender->id, $receiverId)
             ->andReturn((object)[
                 'id' => 10,
                 'sender_id' => $this->sender->id,
                 'receiver_id' => $receiverId,
                 'status' => 'pending',
             ]);

         $response = $this->service->sendRequest($this->sender, $receiverId);

         $this->assertEquals(400, $response->status());
         $this->assertEquals(
             ['message' => 'A friendship request or connection already exists with this user.'],
             $response->getData(true)
         );
     }

    public function test_send_request_creates_friendship_successfully(): void
    {
        $receiverId = 2;

        $this->userMock->shouldReceive('getById')
            ->once()
            ->with($receiverId)
            ->andReturn((object)[
                'id' => $receiverId,
                'email_verified_at' => now(),
            ]);

        $friendshipMock = Mockery::mock('alias:App\Models\Friendship');
        $friendshipMock->shouldReceive('getFriendRequestBetweenUsers')
            ->once()
            ->with($this->sender->id, $receiverId)
            ->andReturn(null);

        $friendshipMock->shouldReceive('createRequest')
            ->once()
            ->with($this->sender->id, $receiverId)
            ->andReturnTrue();

        $response = $this->service->sendRequest($this->sender, $receiverId);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Friendship request sent successfully.', $responseData['message']);
    }
}
