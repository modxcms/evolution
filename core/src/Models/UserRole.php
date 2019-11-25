<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;

/**
 * EvolutionCMS\Models\UserRole
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $frames
 * @property int $home
 * @property int $view_document
 * @property int $new_document
 * @property int $save_document
 * @property int $publish_document
 * @property int $delete_document
 * @property int $empty_trash
 * @property int $action_ok
 * @property int $logout
 * @property int $help
 * @property int $messages
 * @property int $new_user
 * @property int $edit_user
 * @property int $logs
 * @property int $edit_parser
 * @property int $save_parser
 * @property int $edit_template
 * @property int $settings
 * @property int $credits
 * @property int $new_template
 * @property int $save_template
 * @property int $delete_template
 * @property int $edit_snippet
 * @property int $new_snippet
 * @property int $save_snippet
 * @property int $delete_snippet
 * @property int $edit_chunk
 * @property int $new_chunk
 * @property int $save_chunk
 * @property int $delete_chunk
 * @property int $empty_cache
 * @property int $edit_document
 * @property int $change_password
 * @property int $error_dialog
 * @property int $about
 * @property int $category_manager
 * @property int $file_manager
 * @property int $assets_files
 * @property int $assets_images
 * @property int $save_user
 * @property int $delete_user
 * @property int $save_password
 * @property int $edit_role
 * @property int $save_role
 * @property int $delete_role
 * @property int $new_role
 * @property int $access_permissions
 * @property int $bk_manager
 * @property int $new_plugin
 * @property int $edit_plugin
 * @property int $save_plugin
 * @property int $delete_plugin
 * @property int $new_module
 * @property int $edit_module
 * @property int $save_module
 * @property int $delete_module
 * @property int $exec_module
 * @property int $view_eventlog
 * @property int $delete_eventlog
 * @property int $new_web_user
 * @property int $edit_web_user
 * @property int $save_web_user
 * @property int $delete_web_user
 * @property int $web_access_permissions
 * @property int $view_unpublished
 * @property int $import_static
 * @property int $export_static
 * @property int $remove_locks
 * @property int $display_locks
 * @property int $change_resourcetype
 *
 * Virtual
 * @property-read bool $isAlreadyEdit
 * @property-read null|array $alreadyEditInfo
 * @property-read mixed $already_edit_info
 * @property-read mixed $is_already_edit
 *
 * @mixin \Eloquent
 */
class UserRole extends Eloquent\Model
{
    use Traits\Models\ManagerActions;

	public $timestamps = false;

	protected $casts = [
		'frames' => 'int',
		'home' => 'int',
		'view_document' => 'int',
		'new_document' => 'int',
		'save_document' => 'int',
		'publish_document' => 'int',
		'delete_document' => 'int',
		'empty_trash' => 'int',
		'action_ok' => 'int',
		'logout' => 'int',
		'help' => 'int',
		'messages' => 'int',
		'new_user' => 'int',
		'edit_user' => 'int',
		'logs' => 'int',
		'edit_parser' => 'int',
		'save_parser' => 'int',
		'edit_template' => 'int',
		'settings' => 'int',
		'credits' => 'int',
		'new_template' => 'int',
		'save_template' => 'int',
		'delete_template' => 'int',
		'edit_snippet' => 'int',
		'new_snippet' => 'int',
		'save_snippet' => 'int',
		'delete_snippet' => 'int',
		'edit_chunk' => 'int',
		'new_chunk' => 'int',
		'save_chunk' => 'int',
		'delete_chunk' => 'int',
		'empty_cache' => 'int',
		'edit_document' => 'int',
		'change_password' => 'int',
		'error_dialog' => 'int',
		'about' => 'int',
		'category_manager' => 'int',
		'file_manager' => 'int',
		'assets_files' => 'int',
		'assets_images' => 'int',
		'save_user' => 'int',
		'delete_user' => 'int',
		'save_password' => 'int',
		'edit_role' => 'int',
		'save_role' => 'int',
		'delete_role' => 'int',
		'new_role' => 'int',
		'access_permissions' => 'int',
		'bk_manager' => 'int',
		'new_plugin' => 'int',
		'edit_plugin' => 'int',
		'save_plugin' => 'int',
		'delete_plugin' => 'int',
		'new_module' => 'int',
		'edit_module' => 'int',
		'save_module' => 'int',
		'delete_module' => 'int',
		'exec_module' => 'int',
		'view_eventlog' => 'int',
		'delete_eventlog' => 'int',
		'new_web_user' => 'int',
		'edit_web_user' => 'int',
		'save_web_user' => 'int',
		'delete_web_user' => 'int',
		'web_access_permissions' => 'int',
		'view_unpublished' => 'int',
		'import_static' => 'int',
		'export_static' => 'int',
		'remove_locks' => 'int',
		'display_locks' => 'int',
		'change_resourcetype' => 'int'
	];

	protected $hidden = [
		'change_password',
		'save_password'
	];

	protected $fillable = [
		'name',
		'description',
		'frames',
		'home',
		'view_document',
		'new_document',
		'save_document',
		'publish_document',
		'delete_document',
		'empty_trash',
		'action_ok',
		'logout',
		'help',
		'messages',
		'new_user',
		'edit_user',
		'logs',
		'edit_parser',
		'save_parser',
		'edit_template',
		'settings',
		'credits',
		'new_template',
		'save_template',
		'delete_template',
		'edit_snippet',
		'new_snippet',
		'save_snippet',
		'delete_snippet',
		'edit_chunk',
		'new_chunk',
		'save_chunk',
		'delete_chunk',
		'empty_cache',
		'edit_document',
		'change_password',
		'error_dialog',
		'about',
		'category_manager',
		'file_manager',
		'assets_files',
		'assets_images',
		'save_user',
		'delete_user',
		'save_password',
		'edit_role',
		'save_role',
		'delete_role',
		'new_role',
		'access_permissions',
		'bk_manager',
		'new_plugin',
		'edit_plugin',
		'save_plugin',
		'delete_plugin',
		'new_module',
		'edit_module',
		'save_module',
		'delete_module',
		'exec_module',
		'view_eventlog',
		'delete_eventlog',
		'new_web_user',
		'edit_web_user',
		'save_web_user',
		'delete_web_user',
		'web_access_permissions',
		'view_unpublished',
		'import_static',
		'export_static',
		'remove_locks',
		'display_locks',
		'change_resourcetype'
	];

    protected $managerActionsMap = [
        'actions.new' => 38,
        'id' => [
            'actions.edit' => 35
        ]
    ];

    public static function getLockedElements()
    {
        return evolutionCMS()->getLockedElements(8);
    }

    public function getIsAlreadyEditAttribute()
    {
        return array_key_exists($this->getKey(), self::getLockedElements());
    }

    public function getAlreadyEditInfoAttribute() :? array
    {
        return $this->isAlreadyEdit ? self::getLockedElements()[$this->getKey()] : null;
    }
}
