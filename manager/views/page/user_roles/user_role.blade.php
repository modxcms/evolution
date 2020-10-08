@extends('manager::template.page')
@section('content')

    <form name="userform" method="post" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="a" value="36">
        <input type="hidden" name="mode" value="<?= $modx->getManagerApi()->action ?>">
        <input type="hidden" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">

        <h1>
            <i class="{{ $_style['icon_role'] }}"></i>@if(isset($role->name)){{$role->name}} <small>({{$role->id}})</small> @else {{ ManagerTheme::getLexicon('role_title') }}@endif
        </h1>

        {!!  ManagerTheme::getStyle('actionbuttons.dynamic.savedelete')  !!}

        <div class="tab-page">
            <div class="container container-body">
                <div class="form-group">
                    <div class="row form-row">
                        <div class="col-md-3 col-lg-2">{{ ManagerTheme::getLexicon('role_name') }}:</div>
                        <div class="col-md-9 col-lg-10"><input class="form-control form-control-lg" name="name"
                                                               type="text"
                                                               maxlength="50" @if(isset($_POST['name'])) value="{{$_POST['name']}}"  @else value="{{$role->name}}" @endif/></div>
                    </div>
                    <div class="row form-row">
                        <div class="col-md-3 col-lg-2">{{ ManagerTheme::getLexicon('resource_description') }}:</div>
                        <div class="col-md-9 col-lg-10"><input name="description" type="text" maxlength="255"
                                                               @if(isset($_POST['description'])) value="{{$_POST['description']}}"  @else value="{{$role->description}}" @endif size="60"/></div>
                    </div>
                </div>

                <div class="container">

                    <div class="row">
                        @foreach($groups as $group)
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group">

                                    <h3> {{ ManagerTheme::getLexicon($group->lang_key, $group->name) }}</h3>
                                    @foreach($group->permissions as $permission)
                                    <label class="d-block" for="{{$permission->key}}_check">
                                        @include('manager::form.inputElementRole', [
                                            'type' => 'checkbox',
                                            'name' => 'permissions['.$permission->key.']',
                                            'id' => $permission->key.'_check',
                                            'value' => 1,
                                            'checked' => (isset($permissionsRole[$permission->key]) || $permission->disabled) ? 1 : 0,
                                            'disabled' => $permission->disabled,
                                            'class' => 'click'
                                        ])
                                        {{ ManagerTheme::getLexicon($permission->lang_key, $permission->name) }}
                                    </label>

                                    @endforeach

                                </div>
                            </div>
                            @if($loop->iteration/4 == 0)
                                </div>
                                <hr>
                            @endif
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
        <input type="submit" name="save" style="display:none">
    </form>


@endsection


@push('scripts.bot')

    <script type="text/javascript">
        function changestate(element) {
            documentDirty = true;
            if (parseInt(element.value) === 1) {
                element.value = 0;
            } else {
                element.value = 1;
            }
        }

        var actions = {
            save: function () {
                documentDirty = false;
                form_save = true;
                document.userform.save.click();
            },
            delete: function () {
                if (confirm("{{ ManagerTheme::getLexicon('confirm_delete_role') }}") === true) {
                    document.location.href = "index.php?id=" + document.userform.id.value + "&a=35&action=delete";
                }
            },
            cancel: function () {
                documentDirty = false;
                document.location.href = 'index.php?a=86';
            }
        }

    </script>
@endpush