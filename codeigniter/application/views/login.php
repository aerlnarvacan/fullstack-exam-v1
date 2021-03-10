<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  $this->load->helper('url');

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Leave Management</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .container{
      top: 50px;
      margin: auto;
      align-content: center;
    }

    .card{
      height: 300px;
      margin-top: 20%;
      margin-bottom: auto;
      width: 400px;
      background-color: rgba(0,0,0,0.1) !important;
    }

    .input-group-prepend span{
      width: 50px;
      background-color: #756f6e;
      color: black;
      border:0 !important;
    }

    input:focus{
      outline: 0 0 0 0  !important;
      box-shadow: 0 0 0 0 !important;
    }

    .login_btn{
      color: black;
      background-color: #756f6e;
      width: 100px;
    }

    .login_btn:hover{
      color: black;
      background-color: white;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="d-flex justify-content-center h-100">
      <div class="card">
        <div class="card-header">
          <h3>Sign In</h3>
        </div>
        <div class="card-body">
          <form method="post" action="/login">
            <div class="input-group form-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
              </div>
              <input type="text" name="username" class="form-control" placeholder="username">
            </div>

            <div class="input-group form-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
              </div>
              <input type="password" name="password" class="form-control" placeholder="password">
            </div>

            <div class="input-group form-group">
              <span style="color: red"><?php echo(isset($error) ? $error : '</br>'); ?></i></span>
            </div>
            
            <div class="form-group">
              <input type="submit" value="Login" class="btn float-right login_btn">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>