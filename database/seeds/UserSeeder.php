<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = sprintf("
            INSERT INTO `user`
             (`sUsername`, `sPassword`, `iCreatedTimestamp`, 'iUpdatedTimestamp')
            VALUES
             ('Alex', '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou', 123456789, NULL)
             , ('John', '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou', 123456789, 1561287609)
             , ('Key', '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou', 1561287609, 1561285609)
             , ('Mary', '$2y$10$.IukgQA8BibAzmHNe01U2eJUpaseXNsemWXRdM5GXTFyJxShzSvou', 1561287609, 1561285609)");
        DB::insert($sql);
    }
}
