<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
        /**
     * The settings to add.
     */
    protected $settings = [
        [
            'key'         => 'logo',
            'name'        => 'Logo',
            'description' => 'Logo de cliente.',
            'field'       => '{"name":"value","label":"Value","type":"image"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_host',
            'name'        => 'MAIL_HOST',
            'description' => 'Host utilitzat en l\'enviament de correus electrònics.',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_port',
            'name'        => 'MAIL_PORT',
            'description' => 'Port utilitzat en l\'enviament de correus electrònics. ',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_username',
            'name'        => 'MAIL_USERNAME',
            'description' => 'Usuari del compte de correu electrònic',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_password',
            'name'        => 'MAIL_PASSWORD',
            'description' => 'Contrasenya del compte de correu electrònic',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_from_address',
            'name'        => 'MAIL_FROM_ADDRESS',
            'description' => 'Correu electrònic del remitent dels correus enviats per la web.',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_from_name',
            'name'        => 'MAIL_FROM_NAME',
            'description' => 'Nom del remitent dels correus enviats per la web.',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_encryption',
            'name'        => 'MAIL_ENCRYPTION',
            'description' => 'Encriptació utilitzada en l\'enviament de correus electrònics. ',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'mail_subcopy',
            'name'        => 'MAIL SUBCOPY',
            'description' => 'Text dins del correu despres de "Gràcies"',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'sms_id_associacio',
            'name'        => 'SMS idAssociacio',
            'description' => 'Identificador de l\'associació que realitzara l\'enviament de SMS',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'sms_api_key',
            'name'        => 'SMS apiKey',
            'description' => 'Api key utilitzada per enviament SMS',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'sms_id_usuari',
            'name'        => 'SMS idUsuari',
            'description' => 'Identificador usuari utilitzat per enviament SMS',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'sms_id_user',
            'name'        => 'SMS idUser',
            'description' => 'Identificador utilitzat per enviament SMS',
            'field'       => '{"name":"value","label":"Value","type":"text"}',
            'active'      => 1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting) {
            $result = DB::table(config('backpack.settings.table_name'))->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }
    }
}
