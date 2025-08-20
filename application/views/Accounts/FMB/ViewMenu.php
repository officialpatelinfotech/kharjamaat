<div class="container margintopcontainer">
  <h1 class="heading text-center pt-5 mb-4">FMB Menu</h1>
  <h5 class="text-center text-info mb-4">For this month</h5>
  <div class="row">
    <div class="col-md-12 overflow-hidden">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Hijri Date</th>
            <th>Eng Date</th>
            <th>Day</th>
            <th>Menu Items</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($menus)) : ?>
            <?php foreach ($menus as $menu) : ?>
              <tr>
                <td><?php echo $menu["hijri_date"]; ?></td>
                <td><?php echo date("d M Y", strtotime($menu["date"])); ?></td>
                <td><?php echo date("l", strtotime($menu["date"])); ?></td>
                <td><?php echo implode(", ", $menu["items"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="2" class="text-center">No menu items found for this month.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>