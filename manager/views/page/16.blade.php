@extends('manager::template.page')
@section('content')
    <?php include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_templates.dynamic.php"); ?>
@endsection
