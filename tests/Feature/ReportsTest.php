<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ReportsTest extends TestCase
{
    
    use DatabaseTransactions;
    /**
     * Test Reports Page View
     * 
     * @return void
     */
    public function test_reports_view() {
        $user = User::factory(User::class)->create();
        $testGroupID = 0; //Default for groups page
        $response = $this->actingAs($user)->get(route('groups-view', $testGroupID));

        $response->assertStatus(200);
        $response->assertViewIs('groups');
    }
    /**
     * Test Individual Attempt Chart Page View
     * 
     * @return void
     */
    public function test_attempt_chart_page_view() {
        $user = User::factory(User::class)->create();
        $testHistoryID = 3; //Example history ID
        $response = $this->actingAs($user)->get(route('quiz-charts-view', $testHistoryID));

        $response->assertStatus(200);
        $response->assertViewIs('quizcharts');
    }
    /**
     * Test Groups Chart Page View
     * 
     * @return void
     */
    public function test_group_chart_page_view() {
        $user = User::factory(User::class)->create();
        $testGroupID = 2; //Example group
        $response = $this->actingAs($user)->get(route('group-charts-view', $testGroupID));

        $response->assertStatus(200);
        $response->assertViewIs('groupcharts');
    }
}