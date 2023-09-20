  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Worker Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Worker profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                <?php if (!empty($info)): ?>

                  <img class="profile-user-img img-fluid img-circle"
                       src="<?=$info->profile?>"
                       alt="User profile picture">
                <?php endif ?>
                </div>
                <?php if (!empty($info)): ?>
                <h3 class="profile-username text-center"><?=$info->fName.' '.$info->mName.' '.$info->lName?></h3>

                <p class="text-muted text-center"></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Contact No.</b> <a class="float-right"><?=$info->birthDate?></a>
                  </li>

                  <li class="list-group-item">
                    <b>Birthday</b> <a class="float-right"><?=$info->birthDate?></a>
                  </li>

                  <li class="list-group-item">
                    <b>Gender</b> <a class="float-right"><?=gender($info->gender)?></a>
                  </li>
               </ul>

                <?php endif ?>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Job Status</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-university mr-1"></i> Assigned Center</strong>

                <p class="text-muted">
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                <p class="text-muted"><?=$info->address?></p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted"></p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link <?php if(empty($active)){echo "active";}?>" href="#activity" data-toggle="tab">Home</a></li>
                  <li class="nav-item"><a class="nav-link <?php if(!empty($active)){if($active == 'evaluation'){ echo " active";}}?>" href="#timeline" data-toggle="tab">Assessment</a></li>

                  <li class="nav-item"><a class="nav-link <?php if(!empty($active)){if($active == 'student'){ echo " active";}}?>" href="#addStudents" data-toggle="tab">Nutrition</a></li>
                  <?php if (!$this->aauth->is_admin()): ?>
                  <li class="nav-item"><a class="nav-link <?php if(!empty($active)){if($active == 'schoolyear'){ echo " active";}}?>" href="#addSchoolYear" data-toggle="tab"><i class="fa fa-plus"></i></a></li>
                    
                  <?php endif ?>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <div class="card" id="cardWeight"> 
                      <div class="card-header">
                        Enrolled Student by Academic Year
                      </div>
                      <div class="card-body">
                        <div class="row form-responsive">
                          
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>AY</th>
                              <th>Total Student</th>
                              <th>Repeater</th>
                              <th>Graduated</th>
                              <th style="min-width:130px !important">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($myschoolyear)): ?>
                              <?php foreach ($myschoolyear as $key => $val): ?>
                                <tr>
                                  <td><?=$val->YearId?></td>
                                  <td><?=$val->YearStart?> to <?=$val->YearEnd?></td>
                                  <td><?=$val->totalPupils?></td>
                                  <td><?=$val->totalRepeater?></td>
                                  <td><?=$val->totalGraduates?></td>
                                  <td><a href="<?=site_url('students?year='.$val->YearId.'&worker='.$info->workersId)?>" title="View students" class="btn btn-default btn-sm"><i class="fa fa-list"></i></a> <button title="Delete from my list" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button></td>
                                </tr>
                              <?php endforeach ?>
                            <?php endif ?>
                            
                          </tbody>
                        </table>

                        </div>
                      </div>
                    </div>

                    
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane <?php if(!empty($active)){if($active == 'evaluation'){ echo " active";}}?>" id="timeline">
                    <!-- The timeline -->
                    <div class="card">  
                      <div class="card-header">Student Assessment</div>
                      <div class="card-body">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>AY</th>
                              <th>Date of Assessment</th>
                              <th>Scaled Score</th>
                              <th>Standard Score</th>
                              

                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- /.timeline -->
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane <?php if(!empty($active)){if($active == 'schoolyear'){ echo " active";}}?>" id="addSchoolYear">
                    Add School Year

                    <div class="card d-none">
                      <div class="card-header">
                        
                      </div>
                      <div class="card-body">
                        <form id="frmschoolyear" method="post" action="javascript:void(0)">
                        <div class="row form-group">
                          <div class="col-md-3"><label>Start Date</label></div>
                          <div class="col-md-9"><input type="date" name="startdate" class="form-control" placeholder="Start of school year"></div>
                        </div>

                        <div class="row form-group">
                          <div class="col-md-3"><label>End Date</label></div>
                          <div class="col-md-9"><input type="date" name="enddate" class="form-control" placeholder="End of school year"></div>
                        </div>

                        <div class="row form-group">
                          <div class="col-md-3"><label>&nbsp;</label></div>
                          <div class="col-md-9"><button type="submit" class="btn btn-danger">Add</button></div>
                        </div>
                        </form>
                      </div>
                    </div>

                    <!-- select school year / -->
                    <hr>
                    <form class="form-responsive" id="frmselectschoolyear" method="post" action="<?=site_url('workers/addtomyschoolyear')?>">
                      <div class="row">
                        <div class="col-md-3">&nbsp;</div>
                        <div class="col-md-9"><div class="pull-right"><span id="error-area"></span></div></div>
                      </div>
                      <div class="row form-group">
                        <input type="hidden" name="workersId" value="<?=$info->workersId?>">
                          <div class="col-md-3"><label>Select school year</label></div>
                          <div class="col-md-9">
                            <select class="form-control" id="schoolyears" name="schoolyears" required>
                              <option value="">Select here..</option>
                              <?php if (!empty($schoolyears)): ?>
                                <?php foreach ($schoolyears as $key => $value): ?>
                                  <option value="<?=$value->YearId?>"><?=$value->YearStart?> to <?=$value->YearEnd?></option>
                                <?php endforeach ?>
                              <?php endif ?>
                            </select>
                          </div>
                        </div>

                        <div class="row form-group">
                          <div class="col-md-3"><label>&nbsp;</label></div>
                          <div class="col-md-9"><button type="submit" class="btn btn-danger">Save</button></div>
                        </div>
                    </form>
                    <!-- / select school year -->
                  </div>
                  <div class="tab-pane <?php if(!empty($active)){if($active == 'student'){ echo " active";}}?>" id="addStudents">
                   
                    <?php $this->load->view('workers/nutritions'); ?>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->