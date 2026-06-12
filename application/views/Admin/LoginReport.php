<style>
  .card-header {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    color: white;
  }
  .card-title {
    margin: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>System Login Tracking Report</h1>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          
          <div class="card shadow">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-list-alt mr-2"></i> All System Logins</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="loginLogsTable" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th style="width: 5%">#</th>
                      <th style="width: 15%">Name</th>
                      <th style="width: 10%">ITS / Username</th>
                      <th style="width: 10%">Role ID</th>
                      <th style="width: 15%">Location</th>
                      <th style="width: 10%">IP Address</th>
                      <th style="width: 20%">Device / Browser</th>
                      <th style="width: 15%">Date & Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($logs)): ?>
                      <?php foreach ($logs as $index => $log): ?>
                        <tr>
                          <td><?= $index + 1 ?></td>
                          <td><?= htmlspecialchars($log['name'] ?? 'Unknown') ?></td>
                          <td><?= htmlspecialchars($log['its_id'] ?? '-') ?></td>
                          <td><?= htmlspecialchars($log['role'] ?? '0') ?></td>
                          <td><?= htmlspecialchars($log['location'] ?? 'Unknown') ?></td>
                          <td><?= htmlspecialchars($log['ip_address'] ?? 'Unknown') ?></td>
                          <td><small><?= htmlspecialchars($log['user_agent'] ?? 'Unknown') ?></small></td>
                          <td><?= date('d M Y, h:i A', strtotime($log['login_time'])) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center">No login logs found.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Scripts -->
<script>
  $(function () {
    if ($.fn.DataTable) {
      $('#loginLogsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [[7, "desc"]] // Sort by date/time descending
      });
    }
  });
</script>
