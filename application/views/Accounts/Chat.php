<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#b8860b;--gold-muted:#f5e9c0;
  --bg:#faf7f0;--surface:#fff;--surface-2:#f7f4ec;
  --border:#e8e0cc;--border-light:#f0ece0;
  --text-1:#1a1610;--text-2:#5a5244;--text-3:#9c8f7a;
  --blue:#1d4ed8;--blue-bg:#eff6ff;
  --red:#b91c1c;--red-bg:#fef2f2;
  --green:#1a6645;--green-bg:#eaf4ee;
  --sh:0 1px 4px rgba(0,0,0,.07);
  --sh2:0 4px 18px rgba(0,0,0,.10);
}
html,body{height:100%;background:var(--bg)}

#chatApp{
  font-family:'Plus Jakarta Sans',sans-serif;
  display:flex;flex-direction:column;
  height:100vh;max-width:780px;margin:0 auto;
  background:var(--bg);
}

/* ── Top bar ── */
#chatApp .ch-topbar{
  display:flex;align-items:center;gap:10px;
  padding:10px 16px;
  background:var(--surface);
  border-bottom:1px solid var(--border);
  box-shadow:var(--sh);
  flex-shrink:0;
  position:sticky;top:0;z-index:10;
}
#chatApp .ch-back{
  display:inline-flex;align-items:center;gap:5px;
  padding:6px 12px;border-radius:8px;
  border:1.5px solid var(--border);background:var(--surface);
  color:var(--text-2);font-size:.75rem;font-weight:700;
  text-decoration:none;transition:all .15s;white-space:nowrap;
}
#chatApp .ch-back:hover{background:var(--gold-muted);border-color:var(--gold);color:var(--gold);text-decoration:none}
#chatApp .ch-avatar{
  width:36px;height:36px;border-radius:50%;
  background:linear-gradient(135deg,#78520a,var(--gold));
  color:#fff;display:flex;align-items:center;justify-content:center;
  font-weight:800;font-size:.78rem;flex-shrink:0;
}
#chatApp .ch-meta{flex:1;min-width:0}
#chatApp .ch-title{font-weight:800;font-size:.88rem;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
#chatApp .ch-sub{font-size:.68rem;color:var(--text-3);font-weight:500;margin-top:1px}
#chatApp .ch-online{display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--green);margin-right:4px;vertical-align:middle}

/* ── Messages area ── */
#chatApp .ch-msgs{
  flex:1;overflow-y:auto;
  padding:18px 16px;
  display:flex;flex-direction:column;gap:12px;
  scroll-behavior:smooth;
}
#chatApp .ch-msgs::-webkit-scrollbar{width:5px}
#chatApp .ch-msgs::-webkit-scrollbar-track{background:transparent}
#chatApp .ch-msgs::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}
#chatApp .ch-msgs::-webkit-scrollbar-thumb:hover{background:var(--text-3)}

/* ── Date divider ── */
#chatApp .ch-date-div{
  display:flex;align-items:center;gap:8px;
  font-size:.65rem;font-weight:700;color:var(--text-3);
  text-transform:uppercase;letter-spacing:.5px;margin:4px 0;
}
#chatApp .ch-date-div::before,#chatApp .ch-date-div::after{content:'';flex:1;height:1px;background:var(--border-light)}

/* ── Message bubble ── */
#chatApp .ch-msg{display:flex;flex-direction:column;max-width:72%}
#chatApp .ch-msg.sent{align-self:flex-end;align-items:flex-end}
#chatApp .ch-msg.recv{align-self:flex-start;align-items:flex-start}

#chatApp .ch-msg-name{
  font-size:.65rem;font-weight:800;color:var(--text-3);
  text-transform:uppercase;letter-spacing:.4px;margin-bottom:3px;
}
#chatApp .ch-msg.sent .ch-msg-name{color:var(--gold)}

#chatApp .ch-bubble{
  padding:9px 13px;border-radius:14px;
  font-size:.82rem;line-height:1.5;word-break:break-word;
  position:relative;
}
#chatApp .ch-msg.sent .ch-bubble{
  background:linear-gradient(135deg,#78520a,var(--gold));
  color:#fff;border-bottom-right-radius:4px;
}
#chatApp .ch-msg.recv .ch-bubble{
  background:var(--surface);border:1px solid var(--border);
  color:var(--text-1);border-bottom-left-radius:4px;
  box-shadow:var(--sh);
}

#chatApp .ch-msg-foot{
  display:flex;align-items:center;gap:6px;
  margin-top:3px;
}
#chatApp .ch-msg-time{font-size:.62rem;color:var(--text-3);font-weight:500}
#chatApp .ch-del{
  width:18px;height:18px;border-radius:4px;
  display:inline-flex;align-items:center;justify-content:center;
  background:var(--red-bg);color:var(--red);
  text-decoration:none;font-size:.6rem;
  transition:background .14s;border:none;cursor:pointer;
}
#chatApp .ch-del:hover{background:var(--red);color:#fff}

/* ── Empty state ── */
#chatApp .ch-empty{
  flex:1;display:flex;flex-direction:column;
  align-items:center;justify-content:center;gap:8px;
  color:var(--text-3);padding:32px;
}
#chatApp .ch-empty i{font-size:2.2rem;color:var(--border)}
#chatApp .ch-empty p{font-size:.82rem;font-weight:600}

/* ── Input bar ── */
#chatApp .ch-inputbar{
  padding:10px 16px;
  background:var(--surface);
  border-top:1px solid var(--border);
  box-shadow:0 -2px 8px rgba(0,0,0,.04);
  flex-shrink:0;
}
#chatApp .ch-form{display:flex;align-items:flex-end;gap:8px}
#chatApp .ch-textarea-wrap{flex:1;position:relative}
#chatApp .ch-textarea{
  width:100%;
  padding:9px 12px;
  border:1.5px solid var(--border);border-radius:12px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:.82rem;color:var(--text-1);
  background:var(--surface-2);
  resize:none;outline:none;
  min-height:42px;max-height:140px;
  overflow-y:auto;line-height:1.5;
  transition:border-color .15s,background .15s;
}
#chatApp .ch-textarea:focus{border-color:var(--gold);background:var(--surface)}
#chatApp .ch-textarea::placeholder{color:var(--text-3)}
#chatApp .ch-send{
  width:42px;height:42px;border-radius:12px;border:none;
  background:linear-gradient(135deg,#78520a,var(--gold));
  color:#fff;cursor:pointer;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:.9rem;transition:opacity .15s;
}
#chatApp .ch-send:hover{opacity:.88}
#chatApp .ch-send:active{transform:scale(.96)}
#chatApp .ch-char-hint{font-size:.62rem;color:var(--text-3);text-align:right;margin-top:3px;height:14px}

/* ── Typing indicator ── */
#chatApp .ch-typing{
  display:none;align-items:center;gap:4px;
  padding:4px 8px;
  font-size:.68rem;color:var(--text-3);font-style:italic;
}
#chatApp .ch-typing.show{display:flex}
#chatApp .tdot{width:5px;height:5px;border-radius:50%;background:var(--text-3);animation:tdot .9s infinite}
#chatApp .tdot:nth-child(2){animation-delay:.2s}
#chatApp .tdot:nth-child(3){animation-delay:.4s}
@keyframes tdot{0%,80%,100%{transform:scale(.6);opacity:.4}40%{transform:scale(1);opacity:1}}

@media(max-width:520px){
  #chatApp .ch-msgs{padding:12px 10px}
  #chatApp .ch-inputbar{padding:8px 10px}
  #chatApp .ch-topbar{padding:8px 10px}
  #chatApp .ch-msg{max-width:85%}
}
</style>

<?php
/* ── Avatar initials helper ── */
if (!function_exists('ch_initials')) {
  function ch_initials($name) {
    $parts = explode(' ', trim((string)$name));
    if (count($parts) >= 2) return strtoupper(substr($parts[0],0,1).substr($parts[count($parts)-1],0,1));
    return strtoupper(substr($name,0,2));
  }
}

/* ── Session username ── */
$me = $_SESSION['user']['username'] ?? '';

/* ── Group messages by date ── */
$grouped = [];
if (!empty($chat)) {
  foreach ($chat as $msg) {
    $day = date('Y-m-d', strtotime($msg->created_at));
    $grouped[$day][] = $msg;
  }
}

/* ── Back URL ── */
$back_url = isset($from) ? base_url($from) : base_url('accounts');

/* ── Chat title (raza ID or name if available) ── */
$chat_title = isset($raza_title) ? htmlspecialchars($raza_title) : ('Raza Chat #'.htmlspecialchars($id ?? ''));
$chat_sub   = isset($raza_sub)   ? htmlspecialchars($raza_sub)   : 'Internal Communication';
?>

<div id="chatApp">

  <!-- ── Top bar ── -->
  <div class="ch-topbar">
    <a href="<?php echo $back_url ?>" class="ch-back">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <div class="ch-avatar"><?php echo ch_initials($chat_title) ?></div>
    <div class="ch-meta">
      <div class="ch-title"><?php echo $chat_title ?></div>
      <div class="ch-sub"><span class="ch-online"></span><?php echo $chat_sub ?></div>
    </div>
  </div>

  <!-- ── Messages ── -->
  <?php if (empty($chat)): ?>
  <div class="ch-empty">
    <i class="fa-regular fa-comment-dots"></i>
    <p>No messages yet — start the conversation.</p>
  </div>
  <?php else: ?>
  <div class="ch-msgs" id="chMsgs">

    <?php foreach ($grouped as $day => $msgs):
      /* Date divider label */
      $today     = date('Y-m-d');
      $yesterday = date('Y-m-d', strtotime('-1 day'));
      if ($day === $today)          $day_label = 'Today';
      elseif ($day === $yesterday)  $day_label = 'Yesterday';
      else                          $day_label = date('d M Y', strtotime($day));
    ?>
    <div class="ch-date-div"><?php echo $day_label ?></div>

    <?php foreach ($msgs as $message):
      $is_me  = ($message->user === $me);
      $cls    = $is_me ? 'sent' : 'recv';
      $init   = ch_initials($message->user);
      $time   = date('h:i A', strtotime($message->created_at));
    ?>
    <div class="ch-msg <?php echo $cls ?>">
      <div class="ch-msg-name"><?php echo htmlspecialchars(strtoupper($message->user)) ?></div>
      <div class="ch-bubble"><?php echo nl2br(htmlspecialchars($message->message)) ?></div>
      <div class="ch-msg-foot">
        <span class="ch-msg-time"><?php echo $time ?></span>
        <?php if ($is_me): ?>
        <a href="<?php echo base_url('Accounts/deleteMessage/').$message->id ?>"
           class="ch-del"
           onclick="return confirm('Delete this message?')"
           title="Delete">
          <i class="fa fa-trash"></i>
        </a>
        <?php endif ?>
      </div>
    </div>
    <?php endforeach ?>
    <?php endforeach ?>

  </div>
  <?php endif ?>

  <!-- ── Input bar ── -->
  <div class="ch-inputbar">
    <form id="chForm" action="<?php echo base_url('Accounts/send_message') ?>" method="post">
      <input type="hidden" name="raza_id" value="<?php echo htmlspecialchars($id ?? '') ?>">
      <input type="hidden" name="user"    value="<?php echo htmlspecialchars($me) ?>">
      <div class="ch-form">
        <div class="ch-textarea-wrap">
          <textarea
            id="chMsg"
            name="message"
            class="ch-textarea"
            placeholder="Type a message…"
            rows="1"
            oninput="chAutoResize(this);chCountChars(this)"
            onkeydown="chKeyDown(event)"></textarea>
          <div class="ch-char-hint" id="chHint"></div>
        </div>
        <button type="button" class="ch-send" onclick="chSend()" title="Send message" aria-label="Send">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </div>
    </form>
  </div>

</div><!-- /#chatApp -->

<script>
/* ── Auto-scroll to bottom on load ── */
(function(){
  var msgs = document.getElementById('chMsgs');
  if (msgs) msgs.scrollTop = msgs.scrollHeight;
})();

/* ── Auto-resize textarea ── */
function chAutoResize(el){
  el.style.height = 'auto';
  el.style.height = Math.min(el.scrollHeight, 140) + 'px';
}

/* ── Char counter (optional UX touch) ── */
function chCountChars(el){
  var hint = document.getElementById('chHint');
  if (!hint) return;
  var len = el.value.length;
  hint.textContent = len > 0 ? len + ' chars' : '';
}

/* ── Send on Ctrl+Enter or Cmd+Enter ── */
function chKeyDown(e){
  if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
    e.preventDefault();
    chSend();
  }
}

/* ── Send message ── */
function chSend(){
  var ta = document.getElementById('chMsg');
  if (!ta || !ta.value.trim()) return;
  document.getElementById('chForm').submit();
}
</script>