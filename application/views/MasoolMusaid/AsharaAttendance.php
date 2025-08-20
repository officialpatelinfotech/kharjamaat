<!-- FontAwesome (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-..." crossorigin="anonymous" />

<?php
function attendance_status_cell($u, $day)
{
    $key = $day === 'Ashura' ? 'Ashura' : "Day$day";
    $stat = $u[$key] ?? 'Not Marked';
    $cmt = $u[$day === 'Ashura' ? 'CommentAshura' : "Comment$day"] ?? '';

    $colors = [
        'Attended with Maula' => ['bg' => 'bg-success', 'text' => 'text-white'],
        'Attended in Khar on Time' => ['bg' => 'bg-primary', 'text' => 'text-white'],
        'Attended in Khar Late' => ['bg' => 'bg-warning', 'text' => 'text-dark'],
        'Attended in Other Jamaat' => ['bg' => 'bg-info', 'text' => 'text-dark'],
        'Not attended anywhere' => ['bg' => 'bg-danger', 'text' => 'text-white'],
        'Not in Town' => ['bg' => 'bg-dark', 'text' => 'text-white'],
        'Married Outcaste' => ['bg' => 'bg-secondary', 'text' => 'text-white'],
        'Not Marked' => ['bg' => 'bg-light', 'text' => 'text-dark']
    ];

    $statusConfig = $colors[$stat] ?? ['bg' => 'bg-light', 'text' => 'text-dark'];

    // Day label: D2 to D9, or A for Ashura
    $label = ($day === 'Ashura') ? 'A' : 'D' . $day;

    return "<button class='btn btn-sm {$statusConfig['bg']} {$statusConfig['text']} p-0 m-0 border-0 edit-btn'
                   style='width:24px;height:24px;font-size:11px;'
                   data-its='{$u['ITS_ID']}' data-day='$day'
                   data-status='$stat' data-comment='" . htmlspecialchars($cmt, ENT_QUOTES) . "'
                   title='{$stat}: " . htmlspecialchars($cmt, ENT_QUOTES) . "'>$label</button>";
}


function stats_card($st, $day)
{
    $dayClass = $day === 'Ashura' ? 'bg-danger' : 'bg-primary';
    $dayTitle = $day === 'Ashura' ? 'Ashura' : "Day $day";

    return "
    <div class='card h-100 day-card border-0 shadow-sm' data-day='$day' style='cursor:pointer;'>
        <div class='card-header py-2 text-center $dayClass text-white rounded-top'>
            <strong>$dayTitle</strong>
        </div>
        <div class='card-body p-3 bg-light d-flex flex-column gap-2 small'>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-success border-3 shadow-sm'>
                <div class='text-success'>
                    <i class='fas fa-check-circle me-1'></i><strong>With Maula</strong>
                </div>
                <span class='fw-bold text-success'>" . ($st['with_maula'] ?? 0) . "</span>
            </div>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-primary border-3 shadow-sm'>
                <div class='text-primary'>
                    <i class='fas fa-map-marker-alt me-1'></i><strong>Khar On Time</strong>
                </div>
                <span class='fw-bold text-primary'>" . ($st['khar_on_time'] ?? 0) . "</span>
            </div>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-warning border-3 shadow-sm'>
                <div class='text-warning'>
                    <i class='fas fa-clock me-1'></i><strong>Khar Late</strong>
                </div>
                <span class='fw-bold text-warning'>" . ($st['khar_late'] ?? 0) . "</span>
            </div>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-info border-3 shadow-sm'>
                <div class='text-info'>
                    <i class='fas fa-mosque me-1'></i><strong>Other Jamaat</strong>
                </div>
                <span class='fw-bold text-info'>" . ($st['other_jamaat'] ?? 0) . "</span>
            </div>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-danger border-3 shadow-sm'>
                <div class='text-danger'>
                    <i class='fas fa-times-circle me-1'></i><strong>Not Attended</strong>
                </div>
                <span class='fw-bold text-danger'>" . ($st['not_attended'] ?? 0) . "</span>
            </div>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-dark border-3 shadow-sm'>
                <div class='text-dark'>
                    <i class='fas fa-plane me-1'></i><strong>Not in Town</strong>
                </div>
                <span class='fw-bold text-dark'>" . ($st['not_in_town'] ?? 0) . "</span>
            </div>
            <div class='d-flex justify-content-between align-items-center bg-white p-2 rounded border-start border-secondary border-3 shadow-sm'>
                <div class='text-secondary'>
                    <i class='fas fa-user-slash me-1'></i><strong>Outcaste</strong>
                </div>
                <span class='fw-bold text-secondary'>" . ($st['outcaste'] ?? 0) . "</span>
            </div>
        </div>
    </div>";
}



?>

<div class="container-fluid px-0" style="margin-top: 60px;">
    <div class="card border-0 rounded-0">
        <!-- Card Header -->
        <div class="card-header text-white py-3 border-0" style="background: linear-gradient(90deg, #d4af37, #b8860b);">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Left section: Title and sector info -->
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center mr-2">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?= htmlspecialchars($sel_sector . ($sel_sub ? " - $sel_sub" : ""), ENT_QUOTES) ?>
                    </h5>
                    <h5 class="mb-0 fw-semibold d-flex align-items-center">
                        Attendance Dashboard
                    </h5>
                </div>

                <!-- Right section: Count and button aligned together -->
                <div class="d-flex align-items-center gap-3">
                    <div
                        class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2 mb-0 mr-2">
                        <i class="fas fa-users me-1"></i> <?= count($users) ?> Mumineen
                    </div>
                    <?php if (in_array($user_name, ['amilsaheb', 'jamaat'])): ?>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#bulkModal">
                            <i class="fas fa-bolt me-1"></i> Bulk Update
                        </button>
                    <?php endif; ?>

                </div>
            </div>
        </div>


        <!-- Bulk Update Modal -->
        <div class="modal fade" id="bulkModal" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-semibold" id="bulkModalLabel">
                            <i class="fas fa-bolt me-2"></i> Bulk Attendance Update
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3 align-items-center mb-3">
                            <!-- Day Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label mb-1">Select Day</label>
                                <select id="bulkDay" class="form-select form-select-sm">
                                    <?php foreach (range(2, 9) as $d): ?>
                                        <option value="Day<?= $d ?>">Day <?= $d ?></option>
                                    <?php endforeach; ?>
                                    <option value="Ashura">Ashura</option>
                                </select>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label mb-1">Select Status</label>
                                <select id="bulkStatus" class="form-select form-select-sm">
                                    <option>Attended with Maula</option>
                                    <option>Attended in Khar on Time</option>
                                    <option>Attended in Khar Late</option>
                                    <option>Attended in Other Jamaat</option>
                                    <option>Not attended anywhere</option>
                                    <option>Not in Town</option>
                                    <option>Married Outcaste</option>
                                </select>
                            </div>

                            <!-- Update Button -->
                            <div class="col-md-4 d-grid">
                                <label class="form-label invisible">Submit</label>
                                <button id="bulkUpdateBtn" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sync-alt me-1"></i> Update Attendance
                                </button>
                            </div>
                        </div>

                        <!-- ITS Input Area -->
                        <div class="mb-2">
                            <label class="form-label">Paste ITS Numbers (comma or newline separated)</label>
                            <textarea id="bulkITS" rows="5" class="form-control form-control-sm"
                                placeholder="Example: 20312345, 20345678 or each on new line"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div class="card-body p-0">
            <!-- Stats Cards Row -->
            <div class="px-4 pt-3 pb-2 bg-light">
                <div class=" row g-2 row-cols-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-6">
                    <?php foreach ($days as $d): ?>
                        <div class="col">
                            <?= stats_card($stats["Day$d"] ?? [], $d) ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="col">
                        <?= stats_card($stats['Ashura'] ?? [], 'Ashura') ?>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="sticky-top bg-white border-bottom px-4 py-3" style="z-index: 100; top: 60px;">
                <div class="row g-3 align-items-center">

                    <!-- Search Box -->
                    <div class="col-md-6">
                        <div class="input-group input-group-sm shadow-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0"
                                placeholder="Search by Name, ITS, HOF or Sector">
                        </div>
                    </div>

                    <!-- Sector Filter -->
                    <div class="col-auto">
                        <label for="sectorFilter" class="form-label mb-0 small text-muted">Sector</label>
                        <select id="sectorFilter" class="form-select form-select-sm shadow-sm">
                            <option value="">All Sectors</option>
                            <?php foreach (array_unique(array_column($users, 'Sector')) as $sector): ?>
                                <?php $cleanSector = htmlspecialchars($sector ?? '-', ENT_QUOTES); ?>
                                <option value="<?= $cleanSector ?>"><?= $cleanSector ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Sub-Sector Filter -->
                    <div class="col-auto">
                        <label for="subSectorFilter" class="form-label mb-0 small text-muted">Sub-Sector</label>
                        <select id="subSectorFilter" class="form-select form-select-sm shadow-sm">
                            <option value="">All Sub-Sectors</option>
                            <?php foreach (array_unique(array_column($users, 'Sub_Sector')) as $sub): ?>
                                <?php $cleanSub = htmlspecialchars($sub ?? '-', ENT_QUOTES); ?>
                                <option value="<?= $cleanSub ?>"><?= $cleanSub ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Zone Filter: Not Attended Anywhere -->
                    <div class="col-auto">
                        <div class="border rounded-pill px-3 py-1 d-flex align-items-center shadow-sm bg-light">
                            <div class="form-check mb-0">
                                <input class="form-check-input me-2" type="checkbox" id="zoneFilter">
                                <label class="form-check-label small fw-semibold" for="zoneFilter"
                                    style="cursor: pointer;">
                                    <i class="fas fa-user-slash me-1 text-danger"></i>
                                    Not Attended Anywhere
                                </label>
                            </div>
                        </div>
                    </div>


                </div>
            </div>



            <!-- Attendance Table -->
            <div class="px-4 pb-4">
                <div class="table-responsive">
                    <table id="attendanceTable" class="table table-sm table-hover align-middle" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2">HOF ID</th>
                                <th class="py-2">ITS</th>
                                <th class="py-2">Name</th>
                                <th class="py-2" style="width: 110px;">Contact</th>
                                <th class="py-2">Sector</th>
                                <th class="py-2">Sub-Sector</th>
                                <?php foreach ($days as $d): ?>
                                    <th class="text-center py-2" style="width:28px;">
                                        D
                                        <?= htmlspecialchars($d, ENT_QUOTES) ?>
                                    </th>
                                <?php endforeach; ?>
                                <th class="text-center py-2 bg-danger text-red" style="width:28px;">A</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr class="border-top">
                                    <td class="fw-semibold">
                                        <?= htmlspecialchars($u['HOF_ID'] ?? '-', ENT_QUOTES) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($u['ITS_ID'] ?? '-', ENT_QUOTES) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($u['Full_Name'] ?? '-', ENT_QUOTES) ?>
                                    </td>
                                    <td style="width: 110px; white-space: nowrap;">
                                        <?= htmlspecialchars($u['Mobile'] ?? '-', ENT_QUOTES) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($u['Sector'] ?? '-', ENT_QUOTES) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($u['Sub_Sector'] ?? '-', ENT_QUOTES) ?>
                                    </td>
                                    <?php foreach ($days as $d): ?>
                                        <td class="text-center p-0">
                                            <?= attendance_status_cell($u, $d) ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-center p-0">
                                        <?= attendance_status_cell($u, 'Ashura') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

        <!-- Card Footer -->
        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2 px-4 border-0">
            <small class="text-muted">Updated:
                <?= date('M j, Y H:i') ?>
            </small>
            <button id="exportBtn" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Edit Attendance Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow-lg" id="attendanceForm">
            <input type="hidden" id="editIts">
            <input type="hidden" id="editDay">

            <div class="modal-header bg-primary text-white border-0">
                <h5 id="modalTitle" class="modal-title">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Update Attendance
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Status</label>
                    <select class="form-select shadow-sm" id="editStatus">
                        <option>Attended with Maula</option>
                        <option>Attended in Khar on Time</option>
                        <option>Attended in Khar Late</option>
                        <option>Attended in Other Jamaat</option>
                        <option>Not attended anywhere</option>
                        <option>Not in Town</option>
                        <option>Married Outcaste</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Comment</label>
                    <textarea id="editComment" class="form-control shadow-sm" rows="3"
                        placeholder="Add any notes..."></textarea>
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" id="saveAttendance" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Include SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Day Details Modal -->
<div class="modal fade" id="dayDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 id="dayDetailTitle" class="modal-title">
                    <i class="fas fa-calendar-day me-2"></i>Day Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Filter Bar -->
                <div class="px-3 pt-3 pb-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <select id="filterStatus" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option>Attended with Maula</option>
                                <option>Attended in Khar on Time</option>
                                <option>Attended in Khar Late</option>
                                <option>Attended in Other Jamaat</option>
                                <option>Not attended anywhere</option>
                                <option>Not in Town</option>
                                <option>Married Outcaste</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="filterSector" class="form-select form-select-sm">
                                <option value="">All Sectors</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="filterSubSector" class="form-select form-select-sm">
                                <option value="">All Sub-Sectors</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive px-3">
                    <table id="dayDetailTable" class="table table-striped table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>ITS</th>
                                <th>Name</th>
                                <th>HOF</th>
                                <th>Mobile</th>
                                <th>Sector</th>
                                <th>Sub-Sector</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th class="no-export">Edit</th>
                            </tr>
                        </thead>
                        <tbody id="dayDetailBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-success shadow-sm" id="exportDayDetailsBtn">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </button>

            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("dayDetailTable");
        const headers = table.querySelectorAll("thead th");
        let sortDirection = {}; // store sort state for each column

        headers.forEach((th, index) => {
            if (!th.classList.contains('no-export')) {
                th.style.cursor = "pointer";
                th.addEventListener("click", function () {
                    const direction = sortDirection[index] === "asc" ? "desc" : "asc";
                    sortDirection[index] = direction;
                    sortTableByColumn(table, index, direction);
                });
            }
        });

        function sortTableByColumn(table, columnIndex, direction = "asc") {
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr")).filter(r => r.style.display !== 'none');

            rows.sort((a, b) => {
                const cellA = a.children[columnIndex].innerText.trim().toLowerCase();
                const cellB = b.children[columnIndex].innerText.trim().toLowerCase();

                // Try numeric comparison
                const valA = isNaN(cellA) ? cellA : parseFloat(cellA);
                const valB = isNaN(cellB) ? cellB : parseFloat(cellB);

                if (valA < valB) return direction === "asc" ? -1 : 1;
                if (valA > valB) return direction === "asc" ? 1 : -1;
                return 0;
            });

            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        }
    });
</script>

<script>
    document.getElementById('exportDayDetailsBtn').addEventListener('click', function () {
        const table = document.getElementById('dayDetailTable');
        const rows = table.querySelectorAll('tbody tr');
        const data = [];

        console.clear();
        console.log("✅ Export started...");

        // Extract headers (excluding last column)
        const headers = Array.from(table.querySelectorAll('thead th'))
            .slice(0, -1) // remove Edit column
            .map(th => th.innerText.trim());

        console.log("Headers found:", headers);
        data.push(headers);

        // Loop over each row
        let visibleRowCount = 0;
        rows.forEach((row, index) => {
            if (row.offsetParent !== null && row.style.display !== 'none') {
                const cells = Array.from(row.querySelectorAll('td'))
                    .slice(0, -1) // exclude Edit column
                    .map(td => td.innerText.trim());

                console.log(`Row ${index + 1}:`, cells);
                data.push(cells);
                visibleRowCount++;
            }
        });

        if (visibleRowCount === 0) {
            console.warn("⚠️ No visible rows found to export.");
            alert("No visible data to export.");
            return;
        }

        console.log("✅ Total visible rows collected:", visibleRowCount);

        // Generate and download Excel
        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Day Details");

        console.log("📦 Workbook ready. Triggering download...");
        XLSX.writeFile(workbook, `day_details_${new Date().toISOString().slice(0, 10)}.xlsx`);
        console.log("✅ Export completed.");
    });
</script>





<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title"><i class="fas fa-file-export me-2"></i>Export Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Select Days to Export</label>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="selectAllDays" checked>
                        <label class="form-check-label fw-medium" for="selectAllDays">All Days</label>
                    </div>

                    <div class="row mt-2 g-2">
                        <?php foreach ($days as $d): ?>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input day-checkbox" type="checkbox"
                                           id="day<?= $d ?>" value="Day<?= $d ?>" checked>
                                    <label class="form-check-label" for="day<?= $d ?>">Day <?= $d ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input day-checkbox" type="checkbox" id="ashura" value="Ashura" checked>
                                <label class="form-check-label" for="ashura">Ashura</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" id="confirmExport" class="btn btn-primary shadow-sm">
                    <i class="fas fa-download me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(function () {
        const table = $('#attendanceTable').DataTable({
            dom: '<"top">rt<"bottom"i>',
            paging: false,
            scrollY: "65vh",
            scrollCollapse: true,
            order: [[1, 'asc']],
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 2 },
                { responsivePriority: 3, targets: -1 }
            ],
            language: {
                search: "",
                searchPlaceholder: "",
                info: "Showing _MAX_ entries",
                infoEmpty: "No entries found"
            }
        });

        // Filter logic
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
        $('#clearSearch').click(() => $('#searchInput').val('').trigger('keyup'));
        $('#sectorFilter, #subSectorFilter').change(function () {
            table.column(4).search($('#sectorFilter').val() ?? '')
                .column(5).search($('#subSectorFilter').val() ?? '').draw();
        });

        // Open edit modal
        $(document).on('click', '.edit-btn', function () {
            openEditModal($(this).data());
        });

        // Save attendance update
        $('#saveAttendance').click(function () {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

            const its = $('#editIts').val();
            const day = $('#editDay').val();
            const status = $('#editStatus').val();
            const comment = $('#editComment').val();
            const label = (day === 'Ashura') ? 'A' : 'D' + day;

            const colorMap = {
                'Attended with Maula': ['bg-success', 'text-white'],
                'Attended in Khar on Time': ['bg-primary', 'text-white'],
                'Attended in Khar Late': ['bg-warning', 'text-dark'],
                'Attended in Other Jamaat': ['bg-info', 'text-dark'],
                'Not attended anywhere': ['bg-danger', 'text-white'],
                'Not in Town': ['bg-dark', 'text-white'],
                'Married Outcaste': ['bg-secondary', 'text-white'],
                'Not Marked': ['bg-light', 'text-dark']
            };

            $.post('<?php echo base_url("MasoolMusaid/update_attendance") ?>', { its, day, status, comment })
                .done(() => {
                    // console.log("✔️ Update successful");
                    // console.log("Data sent:", { its, day, status, comment });

                    const [bg, text] = colorMap[status] || ['bg-light', 'text-dark'];
                    const $btn = $(`.edit-btn[data-its='${its}'][data-day='${day}']`);
                    $btn.removeClass().addClass(`btn btn-sm ${bg} ${text} p-0 m-0 border-0 edit-btn`)
                        .css({ width: '24px', height: '24px', fontSize: '11px' })
                        .text(label)
                        .attr({
                            'data-status': status,
                            'data-comment': comment,
                            'title': `${status || 'Not Marked'}: ${comment || ''}`
                        });

                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                })
                .fail((xhr) => {
                    // console.error("❌ Update failed. Server response:", xhr.responseText);
                    // console.log("Data sent:", { its, day, status, comment });
                    alert('Update failed. Please try again.');
                })
                .always(() => {
                    btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
                });

        });

        // Open Day Detail Modal
        $('.day-card').click(function () {
            const day = $(this).data('day');
            $('#dayDetailTitle').html(`<i class="fas fa-calendar-day me-2"></i> Attendance Details - ${day === 'Ashura' ? 'Ashura' : 'Day ' + day}`);
            const tb = $('#dayDetailBody').empty();

            <?php foreach ($users as $u): ?>
                    (function (u) {
                        const key = day === 'Ashura' ? 'Ashura' : 'Day' + day;
                        const commentKey = day === 'Ashura' ? 'CommentAshura' : 'Comment' + day;
                        const s = u[key] ?? 'Not Marked';
                        const c = u[commentKey] ?? '';

                        const statusClass = {
                            'Attended with Maula': 'text-success',
                            'Attended in Khar on Time': 'text-primary',
                            'Attended in Khar Late': 'text-warning',
                            'Attended in Other Jamaat': 'text-info',
                            'Not attended anywhere': 'text-danger',
                            'Not in Town': 'text-dark',
                            'Married Outcaste': 'text-secondary',
                            'Not Marked': 'text-muted'
                        }[s] || 'text-muted';

                        tb.append(`
                        <tr>
                            <td>${u.ITS_ID ?? '-'}</td>
                            <td>${u.Full_Name ?? '-'}</td>
                            <td>${u.HOF_ID ?? '-'}</td>
                            <td>${u.Mobile ?? '-'}</td>
                            <td>${u.Sector ?? '-'}</td>
                            <td>${u.Sub_Sector ?? '-'}</td>
                            <td class="${statusClass}">${s}</td>
                            <td>${c}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary p-1 px-2 edit-from-detail"
                                    data-its="${u.ITS_ID}" data-day="${day}"
                                    data-status="${s}" data-comment="${c}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    })(<?= json_encode($u, JSON_HEX_TAG) ?>);
            <?php endforeach; ?>

            populateDayDetailFilters();
            filterDayDetailTable();
            $('#dayDetailModal').modal('show');
        });

        $(document).on('click', '.edit-from-detail', function () {
            const data = $(this).data();
            $('#dayDetailModal').modal('hide');
            openEditModal(data);
        });

        function openEditModal({ its, day, status, comment }) {
            $('#editIts').val(its);
            $('#editDay').val(day);
            $('#editStatus').val(status);
            $('#editComment').val(comment);
            const label = (day === 'Ashura') ? 'Ashura' : 'Day ' + day;
            $('#modalTitle').html(`<i class="fas fa-user-edit me-2"></i> Update Attendance - ${label}`);
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function populateDayDetailFilters() {
            const sectors = new Set();
            const subs = new Set();
            $('#dayDetailBody tr').each(function () {
                sectors.add($(this).find('td:nth-child(5)').text().trim());
                subs.add($(this).find('td:nth-child(6)').text().trim());
            });

            $('#filterSector').html('<option value="">All Sectors</option>' +
                [...sectors].sort().map(s => `<option>${s}</option>`).join(''));
            $('#filterSubSector').html('<option value="">All Sub-Sectors</option>' +
                [...subs].sort().map(s => `<option>${s}</option>`).join(''));
        }

        function filterDayDetailTable() {
            const status = $('#filterStatus').val() ?? '';
            const sector = $('#filterSector').val() ?? '';
            const sub = $('#filterSubSector').val() ?? '';

            $('#dayDetailBody tr').each(function () {
                const s = $(this).find('td:nth-child(7)').text().trim();
                const sec = $(this).find('td:nth-child(5)').text().trim();
                const subSec = $(this).find('td:nth-child(6)').text().trim();
                const match = (!status || s === status) &&
                    (!sector || sec === sector) &&
                    (!sub || subSec === sub);
                $(this).toggle(match);
            });
        }

        $('#filterStatus, #filterSector, #filterSubSector').on('change', filterDayDetailTable);

        // Bulk Update
        $('#bulkUpdateBtn').click(function () {
            const day = $('#bulkDay').val();
            const status = $('#bulkStatus').val();
            const rawITS = $('#bulkITS').val();

            const itsList = rawITS.split(/[\n,]+/).map(i => i.trim()).filter(i => i.length > 0);

            if (!itsList.length) {
                alert("Enter at least one valid ITS number.");
                return;
            }

            if (!confirm(`Confirm update of ${itsList.length} records to '${status}' for ${day}?`)) return;

            // Debug log before sending
            console.log("📤 Sending bulk update:");
            console.log("Day:", day);
            console.log("Status:", status);
            console.log("ITS List:", itsList);

            $.ajax({
                url: '<?php echo base_url("MasoolMusaid/bulk_update_attendance") ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ its_list: itsList, day, status }),
                success: function (response) {
                    console.log("✅ Server responded with success:", response);
                    alert('Bulk update successful.');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("❌ Server responded with error:");
                    console.error("Status:", status);
                    console.error("Error:", error);
                    console.error("Response Text:", xhr.responseText);
                    alert('Update failed. Please check your input or try again.');
                }
            });
        });


        // Export
        $('#exportBtn').click(() => {
            new bootstrap.Modal('#exportModal').show();
        });

        // Toggle all checkboxes when "Select All Days" is changed
        $('#selectAllDays').change(() => {
            const isChecked = $('#selectAllDays').prop('checked');
            $('.day-checkbox').prop('checked', isChecked);
            console.log("Select All Days:", isChecked);
        });


        // Confirm Export Button
        $('#confirmExport').click(function () {
            const selectedDays = $('.day-checkbox:checked').map(function () {
                return $(this).val().trim();
            }).get();

            console.log("Selected Days:", selectedDays);

            const headers = ['ITS_ID', 'Full_Name', 'HOF_ID', 'Mobile', 'Sector', 'Sub_Sector', ...selectedDays];
            console.log("CSV Headers:", headers);

            const exportData = [];

            <?php foreach ($users as $u): ?>
                    (function (u) {
                        const row = [
                            u.ITS_ID ?? '-',
                            u.Full_Name ?? '-',
                            u.HOF_ID ?? '-',
                            u.Mobile ?? '-',
                            u.Sector ?? '-',
                            u.Sub_Sector ?? '-'
                        ];

                        selectedDays.forEach(day => {
                            row.push(u[day] ?? 'Not Marked');
                        });

                        console.log("Row for:", u.Full_Name, row);
                        exportData.push(row);
                    })(<?= json_encode($u, JSON_HEX_TAG) ?>);
            <?php endforeach; ?>

            const csvString = headers.join(',') + '\n' + exportData.map(row => row.join(',')).join('\n');
            console.log("CSV Content:\n", csvString);

            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `ashara_attendance_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            $('#exportModal').modal('hide');
        });




        const absentStatuses = ['not attended anywhere', 'not marked', '-', ''];

        // Custom filter to show only rows where all attendance fields are absent
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const onlyAbsent = $('#zoneFilter').prop('checked');
            if (!onlyAbsent) return true;

            const table = $('#attendanceTable').DataTable();
            const totalCols = table.columns().count();
            const startCol = 6; // Day2 (D2) starts from column index 6

            // console.log(`🔍 Checking row ${dataIndex} from column ${startCol} to ${totalCols - 1}`);

            for (let col = startCol; col < totalCols; col++) {
                const cell = table.cell(dataIndex, col).node();
                const status = $(cell).find('button').data('status')?.toLowerCase().trim() || '';

                // console.log(`➡️ Row ${dataIndex} | Col ${col} | Status: "${status}"`);

                if (!absentStatuses.includes(status)) {
                    // console.log(`❌ Row ${dataIndex} is NOT fully absent (column ${col} is "${status}")`);
                    return false;
                }
            }

            // console.log(`✅ Row ${dataIndex} is fully absent`);
            return true;
        });

        // Trigger table redraw when checkbox is clicked
        $('#zoneFilter').on('change', function () {
            // console.log("🔄 Zone Filter Changed: ", $(this).prop('checked'));
            $('#attendanceTable').DataTable().draw();
        });




    });
</script>






<!-- Styles -->
<style>
    :root {
        --primary: #4e73df;
        --primary-hover: #2e59d9;
        --secondary: #858796;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --light: #f8f9fc;
        --dark: #5a5c69;
    }

    body {
        background-color: #f8f9fc;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .card-header {
        font-weight: 600;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #224abe 100%);
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: var(--dark);
        background-color: #f8f9fc !important;
    }

    .table td {
        vertical-align: middle;
        padding: 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05) !important;
    }

    .form-control,
    .form-select {
        border-radius: 0.375rem !important;
        border: 1px solid #d1d3e2;
    }

    .input-group-text {
        background-color: white;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
    }

    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .day-card {
        transition: all 0.3s ease;
    }

    .day-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.375rem !important;
        padding: 0.375rem 0.75rem !important;
    }

    .dataTables_scrollBody {
        border-bottom: none !important;
    }

    .modal-content {
        border: none;
        border-radius: 0.5rem;
    }

    .modal-header {
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
    }

    .bg-success {
        background-color: var(--success) !important;
    }

    .bg-warning {
        background-color: var(--warning) !important;
    }

    .bg-danger {
        background-color: var(--danger) !important;
    }

    .bg-secondary {
        background-color: var(--secondary) !important;
    }

    .text-success {
        color: var(--success) !important;
    }

    .text-warning {
        color: var(--warning) !important;
    }

    .text-danger {
        color: var(--danger) !important;
    }

    .text-secondary {
        color: var(--secondary) !important;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    @media (max-width: 768px) {
        .card-header h4 {
            font-size: 1.25rem;
        }

        .table-responsive {
            border: none;
        }
    }
</style>