<?php
  session_start();
  include_once '../../chrono-crud.php';
  stopSession();
  // checking session if it's existing
  checkSession('user-session', 'index');
  echo session('user-session');