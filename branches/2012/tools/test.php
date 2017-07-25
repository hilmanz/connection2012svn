<?php
include "Form.php";
$form = new Form();
$form->add("Nama","nama","text");
$form->add("Umur","umur","text");
$form->add("Description","description","textarea");
$form->add("Gender","gender","select");
$form->add("Favorit","fave","radio");
print nl2br(htmlentities($form));
?>