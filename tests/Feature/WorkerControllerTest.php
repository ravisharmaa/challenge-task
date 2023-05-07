<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_get_all_the_workers()
    {
        $this->getJson(route('workers.index'))->assertOk();
    }
}
