<?php
/********************************************************************/
/* default action: show not implemented message                     */
/********************************************************************/
// say that what was requested doesn't do anything yet
?>
@extends('manager::template.page')
@section('content')
    <div class='sectionHeader'>{{ $modx->get('ManagerTheme')->getLexicon('functionnotimpl') }}</div>
    <div class='sectionBody'>
        <p>{{ $modx->get('ManagerTheme')->getLexicon('functionnotimpl_message')  }}</p>
    </div>
@endsection
