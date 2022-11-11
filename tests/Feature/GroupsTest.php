<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\GroupMemberTableSeeder;
use Database\Seeders\GroupTableSeeder;
use Database\Seeders\UserTableSeeder;

class GroupsTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test Groups Page View
     * 
     * @return void
     */
    public function test_groups_view() {
        $user = User::factory(User::class)->create();
        $testGroupID = 0; //Default for groups page
        $response = $this->actingAs($user)->get(route('groups-view', $testGroupID));

        $response->assertStatus(200);
        $response->assertViewIs('groups');
    }
    /**
     * Test Create Group Page View
     * 
     * @return void
     */
    public function test_create_group_view() {
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('create-group-view'));

        $response->assertStatus(200);
        $response->assertViewIs('creategroup');
    }
    /**
     * Test Create New Group View
     * @dataProvider createGroupDataProvider
     * @return void
     */
    public function test_create_new_group($group_name, $group_desc, $subject_id, $public) {
        $user = User::factory(User::class)->create();
        $response = $this->actingAs($user)->from(route('create-group-view'))->post(route('create-group'), [
            'group_name' => $group_name,
            'group_desc' => $group_desc,
            'subject_id' => $subject_id,
            'public' => $public
        ]);
        $this->assertDatabaseHas('groups', [
            'group_name' => $group_name,
            'group_desc' => $group_desc,
            'subject_id' => $subject_id,
            'public' => $public
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('groups-view', 37)); //Test group ID
    }
    public function createGroupDataProvider() {
        return array(
            array("Test Group", "Test description", 1, 1),
        );
    }
    /**
     * Test Join Group
     * 
     * @return void
     */
    public function test_join_group() {
        $user = User::factory(User::class)->create();
        $testGroupID = 2; //Example group ID
        $response = $this->actingAs($user)->get(route('join-group', $testGroupID));

        $response->assertStatus(302);
        $response->assertRedirect(route('groups-view', $testGroupID));
    }
    /**
     * Test Add to Group
     * 
     * @return void
     */
    public function test_add_to_group() {
        $user = User::factory(User::class)->create();
        $testGroupID = 29; //Example group ID
        $response = $this->actingAs($user)->from('groups')->post(route('add-to-group', $testGroupID), [
            'user_id' => 510, //Example user ID
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('groups-view', $testGroupID));
    }
    /**
     * Test Remove Group Member
     * 
     * @return void
     */
    public function test_remove_group_member() {
        $this->seed(UserTableSeeder::class);
        $this->seed(GroupTableSeeder::class);
        $this->seed(GroupMemberTableSeeder::class);
        $user = User::factory(User::class)->create();
        $this->withSession(['currGroup' => 29]);
        $testUserID = 510;
        $testGroupID = 29;
        $response = $this->actingAs($user)->get(route('kick-group', $testUserID));

        $response->assertStatus(302);
        $response->assertRedirect(route('groups-view', $testGroupID));
    }
    /**
     * Test Delete Group
     * 
     * @return void
     */
    //Please note that Delete Group and Remove Group Member is mutually exclusive, both cannot be tested at the same time.
    public function test_delete_group() {
        $this->seed(UserTableSeeder::class);
        $this->seed(GroupTableSeeder::class);
        $this->seed(GroupMemberTableSeeder::class);
        $user = User::factory(User::class)->create();
        $this->withSession(['currGroup' => 29]);
        $testGroupID = 29;
        $response = $this->actingAs($user)->get(route('delete-group', $testGroupID));

        $response->assertStatus(302);
        $response->assertRedirect(route('groups-view', 0));
    }
}