<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Pengaman: test TIDAK PERNAH boleh menyentuh database asli (mis. MySQL).
     * Apa pun nilai env/override (DB_CONNECTION, DB_DATABASE) di terminal,
     * setiap test dipaksa memakai SQLite :memory: sebelum migrasi berjalan.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        return $app;
    }
}
