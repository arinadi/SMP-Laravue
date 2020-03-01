<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimeLineTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function user_post_text()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => 'TESTING BODY',
                ]
            ]
        ]);

        $post = Post::first();
        
        $this->assertCount(1, Post::all());

        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('TESTING BODY', $post->body);

        $response->assertStatus(201)
        ->assertJson([
            'data' => [
                'type' => 'posts',
                'post_id' => $post->id,
                'attributes' => [
                    'body' => 'TESTING BODY',
                ]
            ],
            'links' => [
                'self' => url('/posts/'. $post->id),
            ]
        ]);
    }
}
