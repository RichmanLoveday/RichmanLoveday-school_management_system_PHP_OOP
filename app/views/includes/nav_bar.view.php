<?php

use Mpdf\Tag\Em;

 show($this->controller_name()); ?>
<style>
  nav ul .dropdown-item:hover {
    color: black !important;
  }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary fixed-top mb-3">
  <div class="container"> 
  <a class="navbar-brand" href="#">
  <img src="<?= URLROOT ?>/assets/logo.png" class="" alt="" style="width:40px; height: 40px;">
  <?= Auth::getSchool_name(); ?>
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link <?= $this->controller_name() == 'Home' ? 'active' : '' ?>" href="<?= URLROOT ?>">DASHBOARD</a>
          </li>
          <?php if(Auth::access('super_admin')): ?>
            <li class="nav-item">
              <a class="nav-link <?= $this->controller_name() == 'Schools' ? 'active' : '' ?>" href="<?= URLROOT ?>/schools">SCHOOLS</a>
            </li>
          <?php endif; ?>

          <?php if(Auth::access('admin')): ?>
            <li class="nav-item">
              <a class="nav-link <?= $this->controller_name() == 'Users' ? 'active' : '' ?>" href="<?= URLROOT ?>/users">STAFF</a>
            </li>
          <?php endif; ?>

          <?php if(Auth::access('reception')): ?>
            <li class="nav-item">
              <a class="nav-link <?= $this->controller_name() == 'Students' ? 'active' : '' ?>" href="<?= URLROOT ?>/students">STUDENTS</a>
            </li>
          <?php endif; ?>

          <?php if(Auth::access('lecturer') || Auth::getRank() == 'student'): ?>
            <li class="nav-item">
              <a class="nav-link <?= $this->controller_name() == 'Classes' ? 'active' : '' ?>" href="<?= URLROOT ?>/classes">CLASSESS</a>
            </li>
          <?php endif; ?>

          <?php if(Auth::access('lecturer') || Auth::getRank() == 'student'): ?>
            <?php $unsubmited_test = (new Submitted_testM())->get_unsubmitted_test(); ?>
            <li class="nav-item" style="position: relative;">
              <a class="nav-link <?= $this->controller_name() == 'Tests' ? 'active' : '' ?>" href="<?= URLROOT ?>/tests">TESTS</a>
              <?php if($unsubmited_test): ?>
                <span class="badge bg-danger text-white" style="position: absolute; right: 0px; top: -3px; font-size: 10px;"><?= $unsubmited_test?></span>
              <?php endif; ?>
            </li>
          <?php endif; ?>
        
          <?php if(Auth::access('lecturer')): ?>
            <li class="nav-item" style="position: relative;">
              <a class="nav-link <?= $this->controller_name() == 'To_mark' ? 'active' : '' ?>" href="<?= URLROOT ?>/to_mark">TO MARK
                <?php $to_mark_count = (new TestM())->get_mark_count(); ?>
                <?php if($to_mark_count): ?>
                  <span class="badge bg-danger text-white" style="position: absolute; right: 0px; top: -3px; font-size: 10px;"><?= $to_mark_count?></span>
                <?php endif; ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $this->controller_name() == 'Marked' ? 'active' : '' ?>" href="<?= URLROOT ?>/marked">MARKED</a>
            </li>
          <?php endif; ?>
        </ul>

      <ul class="navbar-nav mx-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $this->controller_name() == 'Profile' ? 'active' : '' ?>" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= Auth::getFirstname(); ?>
          </a>
          <ul class="dropdown-menu bg-secondary" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item text-white" href="<?= URLROOT ?>/profile">Profile</a></li>
            <li><a class="dropdown-item text-white" href="<?= URLROOT ?>">Dashboard</a></li>
            <div class="dropdown-divider bg-white"></div> 
            <li><a class="dropdown-item text-white" href="<?= URLROOT ?>/logout">Logout</a></li>
          </ul>
        </li>
      </ul>
      <?php $years = get_years();
      //show($_SESSION['USER']); ?>
      <form class="form-inline ms-3">
          <div class="input-group">
            <select name="year" id="" class="form-control" style="max-width: 65px;">
                <option><?= get_var('year', !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time()))?></option>
              <?php foreach($years as $year): ?>
                <option ><?= $year ?></option>
              <?php endforeach; ?>
            </select>

            <div class="input-group-prepend">
                <button class="input-group-text" id="basic-addon1" style="max-width: 65px; border-top-left-radius: 0px; border-bottom-left-radius: 0px;"><i class="fa fa-arrow-right ms-2"></i>&nbsp;</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</nav>











    