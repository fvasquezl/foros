<?php

use Tests\CreatesApplication;
use Tests\TestsHelper;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    Use CreatesApplication, TestsHelper;
}
