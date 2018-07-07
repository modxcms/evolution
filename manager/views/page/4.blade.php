<?php /** get the mutate page for adding content */ ?>
@extends('manager::template.page')
@section('content')
    <?php include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_content.dynamic.php");?>
@endsection
