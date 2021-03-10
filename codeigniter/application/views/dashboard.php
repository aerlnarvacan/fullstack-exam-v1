<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  $this->load->helper('url');
  define('STATUS_COLOR', ['PENDING' => '#b57714', 'APPROVED' => '#59d12e', 'CANCELLED' => '#b52914', 'DENIED' => '#b52914'])
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Leave Management</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

  <style>
    #leaves_wrapper {
      width: 100%;
    }

    .app-container {
      width: 95%;
      margin: auto;
    }

    .navbar {
      margin-bottom: 20px;
    }

    table.table-bordered.dataTable thead th {
      font-size: 14px;
    }

    table.table-bordered.dataTable tbody td {
      font-size: 12px;
      text-align: 'center';
    }

    th.dt-center, td.dt-center { 
      text-align: center; 
    }
    
  </style>

  <script>
    $(document).ready(function() {
      $('#leaves').DataTable({
      "order": [[ 1, "asc" ]],
      "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ]});

      $('#leavedate').datepicker({
        uiLibrary: 'bootstrap4',
        minDate: () => {
          var date = new Date();
          date.setDate(date.getDate() + 8);
          return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        }
      });
    });

    <?php if ($employee->role == 'user') { ?>
      function fileLeave() {
        const leaveDate = $('#leavedate').val()
        $('#createBtn').attr('disabled','disabled')
        $.post('/leave/', { leaveDate }, (data, status) => {
          $('#createBtn').removeAttr('disabled')
          $('#fileLeaveModal').modal('toggle')

          if (data.status == "success") {
            window.location.href='/home'
          } else {
            alert(`Error: ${data?.data?.message}`)
          }
        })
      }

      function setUpCancelLeave(leaveId) {
        $("#cancelLeaveId").val(leaveId)
        $("#cancelModal").modal('toggle')
      }

      function cancelLeave() {
        const leaveId = $("#cancelLeaveId").val()
        $.post('/leave/cancel', { leaveId }, (data, status) => {
          $('#cancelModal').modal('toggle')

          if (data.status == "success") {
            window.location.href='/home'
          } else {
            alert(`Error: ${data?.data?.message}`)
          }
        })
      }
    <?php } else { ?>
      function setUpApproveLeave(leaveId) {
        $("#approveLeaveId").val(leaveId)
        $("#approveModal").modal('toggle')
      }

      function approveLeave() {
        const leaveId = $("#approveLeaveId").val()
        $.post('/leave/approve', { leaveId }, (data, status) => {
          $('#approveModal').modal('toggle')

          if (data.status == "success") {
            window.location.href='/home'
          } else {
            alert(`Error: ${data?.data?.message}`)
          }
        })
      }

      function setUpDenyLeave(leaveId) {
        $("#denyLeaveId").val(leaveId)
        $("#denyModal").modal('toggle')
      }

      function denyLeave() {
        const leaveId = $("#denyLeaveId").val()
        $.post('/leave/deny', { leaveId }, (data, status) => {
          $('#denyModal').modal('toggle')

          if (data.status == "success") {
            window.location.href='/home'
          } else {
            alert(`Error: ${data?.data?.message}`)
          }
        })
      }
    <?php } ?>
  </script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <span class="navbar-brand" href="#">
      <i class="fa fa-universal-access"></i> Leave Management
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item">
          <button type="button" class="btn btn-primary btn-sm" style="width:100%" onclick="location.href='/logout'">
            <i class="fa fa-sign-out"></i> Logout
          </button>
        </li>
      </ul>
    </div>
  </nav>

  <div class="app-container">
    <div class="row">

      <div class="col-md-3 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center">
              <img src="https://t4.ftcdn.net/jpg/02/23/50/73/360_F_223507349_F5RFU3kL6eMt5LijOaMbWLeHUTv165CB.jpg" alt="alt image" class="rounded-circle" width="150">
              <div class="mt-3">
                <h4><?=$employee->firstName.' '.$employee->lastName?></h4>
                <p class="text-secondary mb-1"><?=$employee->role == 'admin' ? 'Administrator' : 'Employee' ?></p>
                <?php if ($employee->role !== 'admin') { ?>
                  <p class="text-secondary mb-1">Leave Credits: <b><?=$employee->leaves?></b></p>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>

        <?php if ($employee->role == 'user') { ?>
          <div class="card mt-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileLeaveModal">File Leave Request</button>
          </div>
        <?php } ?>
      </div>

      <div class="col-md-9 mb-3">
        <table id="leaves" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Request Date</th>
              <?=$employee->role == 'admin' ? '<th>Employee Name</th>' : ''?>
              <th>Leave Date</th>
              <th>Status</th>
              <th>Updated By</th>
              <th>Updated At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($leaves as $leave) { ?>
              <tr>
                <td><?=$leave->createdAt?></td>
                <?php if ($employee->role == 'admin') {?>
                  <td><?=$leave->empName?></td>
                <?php }?>
                <td><?=$leave->leaveDate?></td>
                <td style="color: <?=STATUS_COLOR[$leave->status]?>"><b><?=$leave->status?><b></td>
                <td><?=$leave->updater ?? ''?></td>
                <td><?=isset($leave->updater) ? $leave->updatedAt : ''?></td>
                <td>
                  <?php if ($employee->role == 'user' && 
                    $leave->status === 'PENDING' &&
                    (new DateTime(date_format(new DateTime(), 'Y-m-d')))->diff(new DateTime($leave->leaveDate))->format('%r%a') > 0) { ?>
                    <button type="button" class="btn btn-danger btn-sm" onClick="setUpCancelLeave('<?=$leave->id?>')">
                      <i class="fa fa-times"></i> Cancel
                    </button>
                  <?php } else if ($employee->role == 'admin' && $leave->status === 'PENDING') { ?>
                    <button type="button" class="btn btn-success btn-sm" onClick="setUpApproveLeave('<?=$leave->id?>')">
                      <i class="fa fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onClick="setUpDenyLeave('<?=$leave->id?>')">
                      <i class="fa fa-times"></i> Deny
                    </button>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($employee->role == 'user') { ?>

      <div class="modal fade" tabindex="-1" id="fileLeaveModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Leave Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body app-container">
              <div class="form-group">
                <label for="leavedate">Leave Date:<span aria-hidden="true" class="required" style="color:red"> *</span></label>
                <input class="form-control" id="leavedate" name="leavedate" autocomplete="off" required/>
              </div>
              <button type="button" id="createBtn" onclick="fileLeave()" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal fade" tabindex="-1" id="cancelModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cancel Leave Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body app-container">
              <input type="hidden" id="cancelLeaveId" name="leaveId" value="" />
              <div class="form-group">
                <label>Are you sure you want to cancel your leave?</label>
              </div>
              <button type="submit" id="cancel-leave" onclick="cancelLeave()" class="btn btn-primary">Confirm</button>
            </div>
          </div>
        </div>
      </div>

    <?php } else { ?>
      
      <div class="modal fade" tabindex="-1" id="approveModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Approve Leave Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body app-container">
              <input type="hidden" id="approveLeaveId" name="leaveId" value="" />
              <div class="form-group">
                <label>Are you sure you want to approve this leave?</label>
              </div>
              <button type="submit" id="approve-leave" onclick="approveLeave()" class="btn btn-primary">Approve</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" tabindex="-1" id="denyModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Deny Leave Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body app-container">
              <input type="hidden" id="denyLeaveId" name="leaveId" value="" />
              <div class="form-group">
                <label>Are you sure you want to deny this leave?</label>
              </div>
              <button type="submit" id="deny-leave" onclick="denyLeave()" class="btn btn-primary">Reject</button>
            </div>
          </div>
        </div>
      </div>

    <?php } ?>
  </div>
<body>
</html>