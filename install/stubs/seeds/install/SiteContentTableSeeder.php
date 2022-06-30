<?php

namespace EvolutionCMS\Installer\Install;

use Illuminate\Database\Seeder;

class SiteContentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('site_content')->delete();

        $resource = \EvolutionCMS\Models\SiteContent::create(
            [
                'type'            => 'document',
                'contentType'     => 'text/html',
                'pagetitle'       => 'Evolution CMS Install Success',
                'longtitle'       => 'Welcome to the Evolution CMS Content Management System',
                'description'     => '',
                'alias'           => 'minimal-base',
                'link_attributes' => '',
                'published'       => 1,
                'pub_date'        => 0,
                'unpub_date'      => 0,
                'parent'          => 0,
                'isfolder'        => 0,
                'introtext'       => '',
                'content'         => '<h3>Install Successful!</h3>
<p>You have successfully installed Evolution CMS.</p>

<h3>Getting Help</h3>
<p>The <a href="http://evo.im/" target="_blank">Evolution CMS Community</a> provides a great starting point to learn all things Evolution CMS, or you can also <a href="http://evo.im/">see some great learning resources</a> (books, tutorials, blogs and screencasts).</p>
<p>Welcome to Evolution CMS!</p>
',
                'richtext'        => 1,
                'template'        => 1,
                'searchable'      => 1,
                'cacheable'       => 1,
                'createdby'       => 1,
                'createdon'       => 1130304721,
                'editedby'        => 1,
                'editedon'        => 1130304927,
                'deleted'         => 0,
                'deletedon'       => 0,
                'deletedby'       => 0,
                'publishedon'     => 1130304721,
                'publishedby'     => 1,
                'menutitle'       => 'Base Install',
                'hide_from_tree'  => 0,
                'privateweb'      => 0,
                'privatemgr'      => 0,
                'content_dispo'   => 0,
                'hidemenu'        => 0,
                'alias_visible'   => 1,

            ]);
        $resource->save();

    }
}
