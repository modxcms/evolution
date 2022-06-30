<?php

namespace EvolutionCMS\Installer\Install;

use Illuminate\Database\Seeder;

class SiteTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('site_templates')->delete();

        \DB::table('site_templates')->insert([
            0 =>
                [
                    'templatename'  => 'Minimal Template',
                    'templatealias' => '',
                    'description'   => 'Default minimal empty template (content returned only)',
                    'editor_type'   => 0,
                    'category'      => 0,
                    'icon'          => '',
                    'template_type' => 0,
                    'content'       => '[*content*]',
                    'locked'        => 0,
                    'selectable'    => 1,
                    'createdon'     => 0,
                    'editedon'      => 0,
                ],
        ]);


    }
}
