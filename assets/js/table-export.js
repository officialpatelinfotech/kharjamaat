/* Generic table export (CSV) for CodeIgniter views.
 * - Auto-detects the main data table on the page.
 * - Exports visible table text to a UTF-8 BOM CSV (Excel-friendly).
 */
(function () {
  'use strict';

  function isElementVisible(el) {
    if (!el) return false;
    if (el.hidden) return false;
    if (el.getAttribute && el.getAttribute('aria-hidden') === 'true') return false;
    var rects = el.getClientRects();
    if (!rects || rects.length === 0) return false;
    var style = window.getComputedStyle(el);
    if (!style) return true;
    if (style.display === 'none' || style.visibility === 'hidden' || parseFloat(style.opacity || '1') === 0) return false;
    return true;
  }

  function normalizeCellText(cell) {
    if (!cell) return '';

    // Prefer form element value if present.
    var input = cell.querySelector && cell.querySelector('input, textarea, select');
    if (input) {
      if (input.tagName === 'SELECT') {
        var opt = input.options[input.selectedIndex];
        return opt ? String(opt.textContent || opt.innerText || '').trim() : '';
      }
      if (typeof input.value === 'string') return input.value.trim();
    }

    // Avoid including text from hidden elements.
    var clone = cell.cloneNode(true);
    if (clone.querySelectorAll) {
      var hiddenNodes = clone.querySelectorAll('[hidden], [aria-hidden="true"], script, style');
      for (var i = hiddenNodes.length - 1; i >= 0; i--) {
        var n = hiddenNodes[i];
        if (n && n.parentNode) n.parentNode.removeChild(n);
      }
    }

    var text = (clone.textContent || clone.innerText || '');
    // Collapse whitespace/newlines to make CSV cleaner.
    text = text.replace(/\s+/g, ' ').trim();
    return text;
  }

  function escapeCsvValue(value) {
    var s = value == null ? '' : String(value);
    if (s.indexOf('"') !== -1) s = s.replace(/"/g, '""');
    // Always quote; it’s safer for commas/newlines.
    return '"' + s + '"';
  }

  function cellColSpan(cell) {
    var span = 1;
    if (cell && typeof cell.colSpan === 'number' && cell.colSpan > 1) span = cell.colSpan;
    return span;
  }

  function buildExcludedColumnIndexMap(table) {
    var excluded = Object.create(null);

    // Prefer thead headers; fallback to first row that has th.
    var headerRow = null;
    if (table.tHead && table.tHead.rows && table.tHead.rows.length) {
      headerRow = table.tHead.rows[table.tHead.rows.length - 1];
    }
    if (!headerRow) {
      var thRow = table.querySelector('tr th');
      if (thRow) headerRow = thRow.parentElement;
    }
    if (!headerRow) return excluded;

    var headerCells = Array.prototype.slice.call(headerRow.querySelectorAll('th, td'));
    var colIndex = 0;

    for (var i = 0; i < headerCells.length; i++) {
      var cell = headerCells[i];
      var span = cellColSpan(cell);
      var text = normalizeCellText(cell).toLowerCase();
      var isAction = (text === 'action' || text === 'actions');
      var isExplicitExcluded = cell && cell.getAttribute && cell.getAttribute('data-export-exclude') === '1';

      if (isAction || isExplicitExcluded) {
        for (var k = 0; k < span; k++) excluded[colIndex + k] = true;
      }

      colIndex += span;
    }

    return excluded;
  }

  function cellOverlapsExcluded(colIndex, span, excludedMap) {
    if (!excludedMap) return false;
    for (var k = 0; k < span; k++) {
      if (excludedMap[colIndex + k]) return true;
    }
    return false;
  }

  function tableToCsv(table) {
    var excludedMap = buildExcludedColumnIndexMap(table);
    var rows = Array.prototype.slice.call(table.querySelectorAll('tr'));
    var lines = [];

    rows.forEach(function (tr) {
      if (!isElementVisible(tr)) return;

      var cells = Array.prototype.slice.call(tr.querySelectorAll('th, td'));
      if (cells.length === 0) return;

      // Skip fully empty rows.
      var values = [];
      var colIndex = 0;
      for (var i = 0; i < cells.length; i++) {
        var cell = cells[i];
        var span = cellColSpan(cell);

        // Per-cell exclusion (useful when no proper header exists)
        var cellExplicitExcluded = cell && cell.getAttribute && cell.getAttribute('data-export-exclude') === '1';
        if (!cellExplicitExcluded && !cellOverlapsExcluded(colIndex, span, excludedMap)) {
          values.push(normalizeCellText(cell));
        }

        colIndex += span;
      }
      var hasAny = values.some(function (v) { return v !== ''; });
      if (!hasAny) return;

      lines.push(values.map(escapeCsvValue).join(','));
    });

    return lines.join('\r\n');
  }

  function scoreTable(table) {
    if (!table || table.getAttribute('data-no-export') === '1') return -1;
    if (!isElementVisible(table)) return -1;

    var tbodyRows = table.tBodies && table.tBodies.length ? table.tBodies[0].rows.length : 0;
    var allRows = table.rows ? table.rows.length : 0;
    var cols = 0;
    if (table.rows && table.rows.length) cols = table.rows[0].cells.length;

    // Prefer tables that look like data tables: have multiple rows and columns.
    return (tbodyRows * 5) + (allRows * 2) + cols;
  }

  function findBestExportTable() {
    // Explicitly marked table wins.
    var explicit = document.querySelector('table[data-export-table="1"], table.export-table');
    if (explicit && scoreTable(explicit) > 0) return explicit;

    var tables = Array.prototype.slice.call(document.querySelectorAll('table'));
    var best = null;
    var bestScore = -1;

    tables.forEach(function (t) {
      // Skip likely layout/email tables.
      if (t.closest && t.closest('footer, header, nav')) return;
      var s = scoreTable(t);
      if (s > bestScore) {
        bestScore = s;
        best = t;
      }
    });

    return bestScore > 0 ? best : null;
  }

  function buildFilename() {
    var title = (document.title || 'export').trim();
    // Replace filesystem-unfriendly characters.
    title = title.replace(/[^a-zA-Z0-9._ -]+/g, '_').replace(/\s+/g, ' ').trim();

    var d = new Date();
    var yyyy = d.getFullYear();
    var mm = String(d.getMonth() + 1).padStart(2, '0');
    var dd = String(d.getDate()).padStart(2, '0');

    return title + ' ' + yyyy + '-' + mm + '-' + dd + '.csv';
  }

  function downloadCsv(csv, filename) {
    // UTF-8 BOM helps Excel read Unicode properly.
    var bom = '\uFEFF';
    var blob = new Blob([bom + csv], { type: 'text/csv;charset=utf-8;' });

    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    setTimeout(function () {
      try { URL.revokeObjectURL(link.href); } catch (e) {}
    }, 2000);
  }

  function setExportButtonVisible(visible) {
    var wrap = document.getElementById('km-export-excel-wrap');
    if (!wrap) return;
    wrap.style.display = visible ? '' : 'none';
  }

  function wireExportButton() {
    var btn = document.getElementById('km-export-excel-btn');
    if (!btn) return;

    function refreshVisibility() {
      var table = findBestExportTable();
      setExportButtonVisible(!!table);
    }

    refreshVisibility();

    // If the page populates tables after load (AJAX), re-check.
    if (window.MutationObserver && document.body) {
      var refreshTimer = null;
      var obs = new MutationObserver(function () {
        if (refreshTimer) return;
        refreshTimer = window.setTimeout(function () {
          refreshTimer = null;
          refreshVisibility();
        }, 250);
      });
      try {
        obs.observe(document.body, { childList: true, subtree: true });
      } catch (e) {
        // ignore
      }
    }

    window.addEventListener('load', refreshVisibility);

    btn.addEventListener('click', function () {
      var selected = findBestExportTable();
      if (!selected) {
        alert('No table found to export.');
        return;
      }

      var csv = tableToCsv(selected);
      if (!csv) {
        alert('Table has no exportable rows.');
        return;
      }

      downloadCsv(csv, buildFilename());
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', wireExportButton);
  } else {
    wireExportButton();
  }
})();
