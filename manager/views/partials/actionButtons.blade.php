<?php
// actions buttons templates
$action = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';
if (!empty($modx->config['global_tabs']) && !isset($_SESSION['stay'])) {
    $_REQUEST['stay'] = 2;
}
if (isset($_REQUEST['stay'])) {
    $_SESSION['stay'] = $_REQUEST['stay'];
} elseif (isset($_SESSION['stay'])) {
    $_REQUEST['stay'] = $_SESSION['stay'];
}
$stay = isset($_REQUEST['stay']) ? $_REQUEST['stay'] : '';
?>
<div id="actions">
    <div class="btn-group">
        @if(isset($select) && isset($save))
            <div class="btn-group">
                <a id="Button1" class="btn btn-success" href="javascript:;" onclick="actions.save();">
                    <i class="fa fa-floppy-o"></i>
                    <span>{{ ManagerTheme::getLexicon('save') }}</span>
                </a>
                <span class="btn btn-success plus dropdown-toggle"></span>
                <select id="stay" name="stay">
                    @if(!empty($addnew))
                        <option id="stay1" value="1" @if($stay == 1)selected="selected"@endif>{{ ManagerTheme::getLexicon('stay_new') }}</option>
                    @endif
                    <option id="stay2" value="2" @if($stay == 2)selected="selected"@endif>{{ ManagerTheme::getLexicon('stay') }}</option>
                    <option id="stay3" value="" @if($stay == '')selected="selected"@endif>{{ ManagerTheme::getLexicon('close') }}</option>
                </select>
            </div>
        @elseif(isset($save))
            <a id="Button1" class="btn btn-success" href="javascript:;" onclick="actions.save();">
                <i class="fa fa-floppy-o"></i>
                <span>{{ ManagerTheme::getLexicon('save') }}</span>
            </a>
        @endif
        @if(isset($dublicate))
            <a id="Button6" class="btn btn-secondary" href="javascript:;" onclick="actions.duplicate();">
                <i class="fa fa-clone"></i>
                <span>{{ ManagerTheme::getLexicon('duplicate') }}</span>
            </a>
        @endif
        @if(isset($delete))
            <a id="Button3" class="btn btn-secondary" href="javascript:;" onclick="actions.delete();">
                <i class="fa fa-trash"></i>
                <span>{{ ManagerTheme::getLexicon('delete') }}</span>
            </a>
        @endif
        @if(isset($cancel))
            <a id="Button5" class="btn btn-secondary" href="javascript:;" onclick="actions.cancel();">
                <i class="fa fa-times-circle"></i>
                <span>{{ ManagerTheme::getLexicon('cancel') }}</span>
            </a>
        @endif
        @if(isset($preview))
            <a id="Button4" class="btn btn-secondary" href="javascript:;" onclick="actions.view();">
                <i class="fa fa-eye"></i>
                <span>{{ ManagerTheme::getLexicon('preview') }}</span>
            </a>
        @endif
    </div>
</div>
