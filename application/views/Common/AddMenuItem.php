<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
#amiApp{font-family:'Plus Jakarta Sans',sans-serif;color:#1a1610;background:#faf7f0;min-height:calc(100vh - 57px);padding:14px 16px}
#amiApp *{box-sizing:border-box}

/* ── Topbar ── */
#amiApp .ami-top{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap}
#amiApp .ami-back{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-size:.75rem;font-weight:700;text-decoration:none;transition:all .15s;flex-shrink:0}
#amiApp .ami-back:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}
#amiApp .ami-heading{font-size:.98rem;font-weight:800;color:#b8860b;flex:1;text-align:center;letter-spacing:.3px}
#amiApp .ami-header-btns{display:flex;gap:7px;flex-shrink:0}
#amiApp .ami-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 13px;border-radius:8px;font-size:.74rem;font-weight:700;text-decoration:none;border:1.5px solid;cursor:pointer;transition:all .15s;white-space:nowrap;font-family:'Plus Jakarta Sans',sans-serif}
#amiApp .ami-btn-gold{background:#b8860b;border-color:#b8860b;color:#fff}
#amiApp .ami-btn-gold:hover{opacity:.87;text-decoration:none;color:#fff}
#amiApp .ami-btn-outline{background:#fff;border-color:#e8e0cc;color:#5a5244}
#amiApp .ami-btn-outline:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}
#amiApp .ami-btn-green{background:#1a6645;border-color:#1a6645;color:#fff}
#amiApp .ami-btn-green:hover{opacity:.87;text-decoration:none;color:#fff}

/* ── Filter card ── */
#amiApp .ami-fc{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;margin-bottom:12px}
#amiApp .ami-fc-head{background:linear-gradient(135deg,#78520a,#b8860b);padding:9px 14px;display:flex;align-items:center;justify-content:space-between;gap:8px}
#amiApp .ami-fc-title{font-size:.78rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:6px}
#amiApp .ami-fc-body{padding:11px 14px}
#amiApp .ami-frow{display:flex;align-items:flex-end;gap:7px;flex-wrap:wrap}
#amiApp .ami-fg{display:flex;flex-direction:column;gap:3px;flex:1;min-width:120px}
#amiApp .ami-lbl{font-size:.61rem;font-weight:800;color:#5a5244;text-transform:uppercase;letter-spacing:.5px}
#amiApp .ami-sel,#amiApp .ami-inp{height:32px;padding:0 9px;border:1.5px solid #e8e0cc;border-radius:7px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:#1a1610;outline:none;transition:border-color .15s,box-shadow .15s;width:100%}
#amiApp .ami-sel:focus,#amiApp .ami-inp:focus{border-color:#b8860b;background:#fff;box-shadow:0 0 0 3px rgba(184,134,11,.1)}
#amiApp .ami-search-btn{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 14px;border-radius:7px;background:#b8860b;border:none;color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif}
#amiApp .ami-search-btn:hover{opacity:.87}
#amiApp .ami-clr{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 11px;border-radius:7px;background:#f7f4ec;border:1.5px solid #e8e0cc;color:#5a5244;font-size:.74rem;font-weight:700;text-decoration:none;white-space:nowrap;transition:all .15s}
#amiApp .ami-clr:hover{background:#fef2f2;border-color:#b91c1c;color:#b91c1c;text-decoration:none}

/* ── Inline add forms (slide-in cards) ── */
#amiApp .ami-add-card{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 2px 10px rgba(0,0,0,.08);margin-bottom:12px;overflow:hidden;display:none}
#amiApp .ami-add-card.open{display:block}
#amiApp .ami-add-card-head{padding:10px 14px;border-bottom:1px solid #f0ece0;background:#f7f4ec;display:flex;align-items:center;justify-content:space-between}
#amiApp .ami-add-card-title{font-size:.78rem;font-weight:800;color:#5a5244;display:flex;align-items:center;gap:6px}
#amiApp .ami-add-card-title i{color:#b8860b}
#amiApp .ami-add-card-body{padding:14px}
#amiApp .ami-add-row{display:flex;align-items:flex-end;gap:8px;flex-wrap:wrap}
#amiApp .ami-field{display:flex;flex-direction:column;gap:3px;flex:1;min-width:140px}
#amiApp .ami-field-lbl{font-size:.62rem;font-weight:700;color:#5a5244;text-transform:uppercase;letter-spacing:.4px}
#amiApp .ami-field-inp,#amiApp .ami-field-sel{height:34px;padding:0 10px;border:1.5px solid #e8e0cc;border-radius:8px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.78rem;color:#1a1610;outline:none;transition:border-color .15s,box-shadow .15s;width:100%}
#amiApp .ami-field-inp:focus,#amiApp .ami-field-sel:focus{border-color:#b8860b;background:#fff;box-shadow:0 0 0 3px rgba(184,134,11,.1)}
#amiApp .ami-submit-btn{display:inline-flex;align-items:center;gap:4px;height:34px;padding:0 16px;border-radius:8px;border:none;background:#1a6645;color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;flex-shrink:0}
#amiApp .ami-submit-btn:hover{opacity:.87}
#amiApp .ami-cancel-btn{display:inline-flex;align-items:center;gap:4px;height:34px;padding:0 12px;border-radius:8px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-size:.75rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;flex-shrink:0}
#amiApp .ami-cancel-btn:hover{background:#f7f4ec}

/* ── Table card ── */
#amiApp .ami-tcard{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden}
#amiApp .ami-tscroll{overflow-x:auto;overflow-y:visible}
#amiApp .ami-tscroll::-webkit-scrollbar{height:4px}
#amiApp .ami-tscroll::-webkit-scrollbar-thumb{background:#e8e0cc;border-radius:10px}
#amiApp table.ami-tbl{width:100%;border-collapse:collapse;font-size:.78rem;min-width:480px}
#amiApp table.ami-tbl thead th{background:linear-gradient(to bottom,#f7f4ec,#ede8da);padding:9px 12px;font-size:.59rem;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:#9c8f7a;border-bottom:2px solid #e8e0cc;white-space:nowrap;text-align:left;}
#amiApp table.ami-tbl tbody tr{border-bottom:1px solid #f0ece0;transition:background .1s}
#amiApp table.ami-tbl tbody tr:hover td{background:#fdf9ef}
#amiApp table.ami-tbl tbody tr:nth-child(even) td{background:#faf7f2}
#amiApp table.ami-tbl tbody tr:nth-child(even):hover td{background:#fdf9ef}
#amiApp table.ami-tbl td{padding:9px 12px;vertical-align:middle;color:#1a1610}

/* Item name / type display vs edit mode */
#amiApp .item-name-display,#amiApp .item-type-display{font-size:.8rem;font-weight:600;color:#1a1610}
#amiApp .edit-item-name,#amiApp .edit-item-type{display:none;height:30px;padding:0 8px;border:1.5px solid #e8e0cc;border-radius:7px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:#1a1610;outline:none;transition:border-color .15s;width:100%}
#amiApp .edit-item-name:focus,#amiApp .edit-item-type:focus{border-color:#b8860b;background:#fff;box-shadow:0 0 0 3px rgba(184,134,11,.1)}

/* Type badge */
#amiApp .type-badge{display:inline-block;background:#f5e9c0;color:#b8860b;border:1px solid rgba(184,134,11,.25);border-radius:20px;padding:2px 8px;font-size:.62rem;font-weight:700;white-space:nowrap}

/* Action buttons */
#amiApp .act-btn{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;border:1.5px solid;font-size:.72rem;text-decoration:none;cursor:pointer;transition:all .14s;background:transparent;font-family:'Plus Jakarta Sans',sans-serif;vertical-align:middle}
#amiApp .act-edit{border-color:#e8e0cc;color:#5a5244}
#amiApp .act-edit:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b}
#amiApp .act-save{border-color:#1a6645;color:#1a6645;display:none}
#amiApp .act-save:hover{background:#eaf4ee}
#amiApp .act-del{border-color:#fecaca;color:#b91c1c}
#amiApp .act-del:hover{background:#fef2f2;border-color:#b91c1c}

/* Footer */
#amiApp .ami-foot{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-top:1px solid #f0ece0;background:#f7f4ec;font-size:.68rem;color:#9c8f7a;flex-wrap:wrap;gap:6px}
#amiApp .ami-cnt{background:#f5e9c0;color:#b8860b;border-radius:20px;padding:2px 9px;font-size:.64rem;font-weight:800}

/* Empty */
#amiApp .ami-empty{text-align:center;padding:40px;color:#9c8f7a;font-size:.82rem}
#amiApp .ami-empty i{font-size:1.8rem;display:block;margin-bottom:8px;color:#e8e0cc}

@media(max-width:576px){
  #amiApp{padding:10px}
  #amiApp .ami-frow,#amiApp .ami-add-row{flex-direction:column}
  #amiApp .ami-fg,#amiApp .ami-field{min-width:100%}
  #amiApp .ami-header-btns{width:100%;justify-content:flex-end}
  #amiApp table.ami-tbl{min-width:380px}
}
</style>

<div id="amiApp" class="margintopcontainer">

  <!-- ① TOP BAR -->
  <div class="ami-top">
    <a href="<?php echo base_url('common/fmbthaalimenu?from='.$from)?>" class="ami-back">
      <i class="fa fa-arrow-left"></i> Back
    </a>
    <div class="ami-heading"><i class="fa fa-list" style="margin-right:6px;opacity:.7"></i>Menu Item List</div>
    <div class="ami-header-btns">
      <button type="button" class="ami-btn ami-btn-outline" id="btn-show-type">
        <i class="fa fa-plus"></i> Add Type
      </button>
      <button type="button" class="ami-btn ami-btn-green" id="btn-show-item">
        <i class="fa fa-plus"></i> Add Item
      </button>
    </div>
  </div>

  <!-- ② ADD TYPE FORM (hidden by default) -->
  <div class="ami-add-card" id="card-add-type">
    <div class="ami-add-card-head">
      <div class="ami-add-card-title"><i class="fa fa-tag"></i> Add New Type</div>
      <button type="button" class="ami-cancel-btn" id="cancel-type"><i class="fa fa-times"></i></button>
    </div>
    <div class="ami-add-card-body">
      <form method="post" action="<?php echo base_url('common/insert_item_type')?>" id="add-item-type">
        <div class="ami-add-row">
          <div class="ami-field">
            <label class="ami-field-lbl">Type Name</label>
            <input type="text" id="type-name" name="type_name" placeholder="e.g. Sabzi, Dal, Roti…" class="ami-field-inp" required>
          </div>
          <input type="submit" class="ami-submit-btn" value="Add Type">
          <button type="button" class="ami-cancel-btn" id="cancel-type-2"><i class="fa fa-times"></i> Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ③ ADD ITEM FORM (hidden by default) -->
  <div class="ami-add-card" id="card-add-item">
    <div class="ami-add-card-head">
      <div class="ami-add-card-title"><i class="fa fa-cutlery"></i> Add New Menu Item</div>
      <button type="button" class="ami-cancel-btn" id="cancel-item"><i class="fa fa-times"></i></button>
    </div>
    <div class="ami-add-card-body">
      <form method="post" action="<?php echo base_url('common/insert_menu_item?from='.$from)?>" id="add-item-form">
        <div class="ami-add-row">
          <div class="ami-field">
            <label class="ami-field-lbl">Item Name</label>
            <input type="text" id="item_name" name="item_name" placeholder="e.g. Chicken Biryani…" class="ami-field-inp" required>
          </div>
          <div class="ami-field">
            <label class="ami-field-lbl">Item Type</label>
            <select name="item_type" id="item_type" class="ami-field-sel" required>
              <option value="" disabled selected>Select type</option>
              <?php foreach($item_types as $v): ?>
              <option value="<?php echo htmlspecialchars($v['type_name'],ENT_QUOTES)?>"><?php echo htmlspecialchars($v['type_name'],ENT_QUOTES)?></option>
              <?php endforeach?>
            </select>
          </div>
          <input type="submit" class="ami-submit-btn" value="Add Item">
          <button type="button" class="ami-cancel-btn" id="cancel-item-2"><i class="fa fa-times"></i> Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ④ FILTER CARD -->
  <div class="ami-fc">
    <div class="ami-fc-head">
      <div class="ami-fc-title"><i class="fa fa-sliders"></i> Filters</div>
    </div>
    <div class="ami-fc-body">
      <form method="post" action="<?php echo base_url('common/filter_menu_item?from='.$from)?>" id="filter-form">
        <div class="ami-frow">
          <div class="ami-fg" style="max-width:170px">
            <label class="ami-lbl">Type</label>
            <select id="filter-type" name="filter-type" class="ami-sel">
              <option value="">All Types</option>
              <?php foreach($item_types as $v): ?>
              <option value="<?php echo htmlspecialchars($v['type_name'],ENT_QUOTES)?>"
                <?php echo(isset($filter_type)&&$filter_type==$v['type_name'])?'selected':''?>>
                <?php echo htmlspecialchars($v['type_name'],ENT_QUOTES)?>
              </option>
              <?php endforeach?>
            </select>
          </div>
          <div class="ami-fg">
            <label class="ami-lbl">Search Item Name</label>
            <input type="text" id="search-item" name="search-item"
              value="<?php echo isset($search_item)?htmlspecialchars($search_item,ENT_QUOTES):''?>"
              class="ami-inp" placeholder="Search by name…">
          </div>
          <div class="ami-fg" style="max-width:170px">
            <label class="ami-lbl">Sort</label>
            <select id="sort-type" name="sort-type" class="ami-sel">
              <option value="asc" <?php echo(isset($sort_type)&&$sort_type==='asc')?'selected':''?>>Name A → Z</option>
              <option value="desc" <?php echo(isset($sort_type)&&$sort_type==='desc')?'selected':''?>>Name Z → A</option>
            </select>
          </div>
          <div style="display:flex;align-items:flex-end;gap:6px">
            <button type="submit" class="ami-search-btn" id="search-btn">
              <i class="fa fa-search"></i> Search
            </button>
            <a href="<?php echo base_url('common/add_menu_item?from='.$from)?>" class="ami-clr">
              <i class="fa fa-times"></i> Clear
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- ⑤ TABLE CARD -->
  <div class="ami-tcard">
    <div class="ami-tscroll">
      <table class="ami-tbl" id="ami-table">
        <thead>
          <tr>
            <th style="min-width:200px">Item Name</th>
            <th style="min-width:120px">Item Type</th>
            <th style="width:100px;text-align:center">Actions</th>
          </tr>
        </thead>
        <tbody id="ami-tbody">
          <?php if(!empty($menu_items)):?>
          <?php foreach($menu_items as $item): ?>
          <tr id="row-<?php echo $item['id']?>">
            <td>
              <span class="item-name-display" id="itemName_<?php echo $item['id']?>"><?php echo htmlspecialchars($item['name'],ENT_QUOTES)?></span>
              <input type="text" value="<?php echo htmlspecialchars($item['name'],ENT_QUOTES)?>"
                id="editItemName_<?php echo $item['id']?>" class="edit-item-name" required>
            </td>
            <td>
              <span class="type-badge" id="itemType_<?php echo $item['id']?>"><?php echo htmlspecialchars($item['type'],ENT_QUOTES)?></span>
              <select id="editItemType_<?php echo $item['id']?>" class="edit-item-type">
                <?php foreach($item_types as $v): ?>
                <option value="<?php echo htmlspecialchars($v['type_name'],ENT_QUOTES)?>"
                  <?php echo(isset($item['type'])&&$item['type']==$v['type_name'])?'selected':''?>>
                  <?php echo htmlspecialchars($v['type_name'],ENT_QUOTES)?>
                </option>
                <?php endforeach?>
              </select>
            </td>
            <td style="text-align:center;white-space:nowrap">
              <button type="button" class="act-btn act-edit" id="edit-btn-<?php echo $item['id']?>"
                onclick="editMenuItem(<?php echo $item['id']?>)" title="Edit">
                <i class="fa fa-pencil"></i>
              </button>
              <a href="<?php echo base_url('common/edit_menu_item/')?>"
                class="act-btn act-save" id="save-btn-<?php echo $item['id']?>"
                onclick="saveItem(<?php echo $item['id']?>);return false;" title="Save">
                <i class="fa fa-check"></i>
              </a>
              <button type="button" class="act-btn act-del" id="delete-btn-<?php echo $item['id']?>"
                onclick="deleteItem(<?php echo $item['id']?>)" title="Delete">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endforeach?>
          <?php else:?>
          <tr>
            <td colspan="3">
              <div class="ami-empty">
                <i class="fa fa-list"></i>
                <p>No items found.</p>
              </div>
            </td>
          </tr>
          <?php endif?>
        </tbody>
      </table>
    </div>
    <div class="ami-foot">
      <span class="ami-cnt"><?php echo!empty($menu_items)?count($menu_items):0?> item<?php echo(!empty($menu_items)&&count($menu_items)!==1)?'s':''?></span>
      <span>Click <i class="fa fa-pencil" style="font-size:.7rem"></i> to edit inline</span>
    </div>
  </div>

</div><!-- /#amiApp -->

<script>
$(document).ready(function(){
  /* ── Add type / Add item card toggles ── */
  $('#btn-show-type').on('click',function(){$('#card-add-type').addClass('open');$('#card-add-item').removeClass('open')});
  $('#btn-show-item').on('click',function(){$('#card-add-item').addClass('open');$('#card-add-type').removeClass('open')});
  $('#cancel-type,#cancel-type-2').on('click',function(){$('#card-add-type').removeClass('open')});
  $('#cancel-item,#cancel-item-2').on('click',function(){$('#card-add-item').removeClass('open')});

  /* ── Hide save button initially ── */
  $('.act-save').hide();
  $('.edit-item-name').hide();
  $('.edit-item-type').hide();
});

function editMenuItem(id){
  /* Hide display, show edit fields */
  $('#edit-btn-'+id).hide();
  $('#save-btn-'+id).css('display','inline-flex');
  $('#itemName_'+id).hide();
  $('#editItemName_'+id).show().focus();
  $('#itemType_'+id).hide();
  $('#editItemType_'+id).show();
}

function saveItem(id){
  var itemName=$('#editItemName_'+id).val();
  var itemType=$('#editItemType_'+id).val();
  var url=$('#save-btn-'+id).attr('href');
  $.ajax({
    url:url,type:'POST',
    data:{id:id,name:itemName,type:itemType},
    success:function(){
      $('#itemName_'+id).text(itemName).show();
      $('#itemType_'+id).text(itemType).show();
      $('#edit-btn-'+id).show();
      $('#save-btn-'+id).hide();
      $('#editItemName_'+id).hide();
      $('#editItemType_'+id).hide();
    },
    error:function(xhr,status,error){console.error('Save error:',error)}
  });
}

function deleteItem(id){
  if(!confirm('Are you sure you want to delete this item?'))return false;
  var url=$('#delete-btn-'+id).attr('href')||'<?php echo base_url('common/delete_menu_item/')?>';
  $.ajax({
    url:url,type:'POST',data:{id:id},
    success:function(){
      $('#row-'+id).fadeOut(200,function(){$(this).remove();
        /* update count */
        var n=$('#ami-tbody tr:visible').length;
        $('.ami-cnt').text(n+' item'+(n!==1?'s':''));
      });
    },
    error:function(xhr,status,error){console.error('Delete error:',error)}
  });
}
</script>