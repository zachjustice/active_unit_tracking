<!DOCTYPE html>
<html lang="en">
    <? require_once('includes/header.php') ?>
    <div class="container" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="page-header">
          <h1>Time Entry</h1>
        </div>
        <p>To begin tracking time for a unit at this station, scan the the unit, rework, or employee barcodes. You can also click the "New Time Entry" button below.</p>
        <button type="button" class="btn btn-default">New Time Entry</button>
        <div class="error-container" style="margin-top: 10px"></div>
        <table class="table table-bordered" style="margin-top: 20px">
            <thead>
                <tr>
                    <th>Station</th>
                    <th>Unit</th>
                    <th>Name</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody id="time_entry_table">
                <tr>
                    <td >Final Assembly</td>
                    <td>RGLMG41812017A</td>
                    <td>Mark Hamill</td>
                    <td>1/1/2017 1:00 PM</td>
                    <td>In Progress</td>
                    <td>4:35:11</td>
                </tr>
                <tr>
                    <td>Final Assembly</td>
                    <td>RGLMG41812017A</td>
                    <td>Mark Otto</td>
                    <td>1/2/2017 1:00 PM</td>
                    <td>1/2/2017 5:00 PM</td>
                    <td>7:00:00</td>
                </tr>
                <tr>
                    <td>Final Assembly</td>
                    <td>RGLMG41812017A</td>
                    <td>Bob Thornton</td>
                    <td>1/2/2017 1:00 PM</td>
                    <td>1/2/2017 5:00 PM</td>
                    <td>8:00:00</td>
                </tr>
            </tbody>
        </table>
      </div>

    </div> <!-- /container -->
    <? require_once( 'templates/error_message.php' ) ?>
    <? require_once('includes/footer.php') ?>
    <script src="js/barcode.js"></script>
    <script src="index.js"></script>
