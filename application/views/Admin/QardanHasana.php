<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
  $qh_prefix = isset($qh_prefix) && trim((string)$qh_prefix) !== '' ? trim((string)$qh_prefix) : 'admin';
  $qh_home_url = base_url($qh_prefix);
  $qh_scheme_base = $qh_prefix . '/qardanhasana';

  $qh_schemes = isset($qh_schemes) && is_array($qh_schemes) && !empty($qh_schemes)
    ? array_values(array_map(function($s){ return strtolower(trim((string)$s)); }, $qh_schemes))
    : ['mohammedi', 'taher', 'husain'];

  $qh_scheme_meta = [
    'mohammedi' => ['cls' => 'qh-card--mohammedi', 'icon' => 'fa-solid fa-hand-holding-heart', 'title' => 'Mohammedi Scheme'],
    'taher' => ['cls' => 'qh-card--taher', 'icon' => 'fa-solid fa-sack-dollar', 'title' => 'Taher Scheme'],
    'husain' => ['cls' => 'qh-card--husain', 'icon' => 'fa-solid fa-handshake', 'title' => 'Husain Scheme'],
  ];

  $qh_scheme_totals = (isset($qh_scheme_totals) && is_array($qh_scheme_totals)) ? $qh_scheme_totals : [];
  $qh_total_all = isset($qh_total_all) ? (float)$qh_total_all : null;
?>

<style>
  .qh-card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
    margin-top: 3rem;
  }

  .qh-card {
    flex: 1 1 20%;
    min-width: 250px;
    max-width: 300px;
    --qh-bg-1: #0f766e;
    --qh-bg-2: #115e59;
    --qh-ink: #ffffff;
    --qh-surface: rgba(255, 255, 255, 0.14);
    background: radial-gradient(120% 120% at 30% 10%, var(--qh-bg-1) 0%, var(--qh-bg-2) 70%);
    border-radius: 18px;
    box-shadow: 0 10px 34px rgba(0, 0, 0, 0.12);
    padding: 2.5rem 1.5rem 2rem 1.5rem;
    text-align: center;
    transition: box-shadow 0.2s, transform 0.2s, background 0.2s;
    position: relative;
    overflow: hidden;
    color: var(--qh-ink);
    border: 1px solid rgba(255, 255, 255, 0.12);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    height: 300px;
  }

  .qh-card.qh-card--mohammedi {
    --qh-bg-1: #0ea05a;
    --qh-bg-2: #065f46;
  }

  .qh-card.qh-card--taher {
    --qh-bg-1: #2563eb;
    --qh-bg-2: #1e3a8a;
  }

  .qh-card.qh-card--husain {
    --qh-bg-1: #b45309;
    --qh-bg-2: #7c2d12;
  }

  .qh-card:hover {
    box-shadow: 0 16px 46px rgba(0, 0, 0, 0.18);
    transform: translateY(-4px) scale(1.04);
    background: radial-gradient(120% 120% at 30% 10%, color-mix(in srgb, var(--qh-bg-1) 90%, #ffffff 10%) 0%, color-mix(in srgb, var(--qh-bg-2) 92%, #000000 8%) 70%);
    border-color: rgba(255, 255, 255, 0.18);
    color: var(--qh-ink);
  }

  .qh-card-icon {
    font-size: 2.7rem;
    margin-bottom: 1.1rem;
    color: var(--qh-ink);
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
    padding: 0.7rem;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.14);
    display: inline-block;
    border: 1px solid rgba(255, 255, 255, 0.16);
  }

  .qh-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.1rem;
    color: var(--qh-ink);
    letter-spacing: 0.5px;
    text-shadow: 0 6px 20px rgba(0, 0, 0, 0.14);
  }

  .qh-card-link {
    display: inline-block;
    margin-top: auto;
    margin-bottom: 0;
    padding: 0.35rem 0rem;
    font-size: 1rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.92);
    color: rgba(0, 0, 0, 0.78);
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  }

  .qh-card-link:hover {
    background: rgba(255, 255, 255, 0.16);
    color: var(--qh-ink);
    box-shadow: 0 10px 26px rgba(0, 0, 0, 0.16);
    border-color: rgba(255, 255, 255, 0.22);
    text-decoration-line: none;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo $qh_home_url; ?>" class="btn btn-outline-secondary" aria-label="Back">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
    </div>
  </div>

  <h4 class="heading text-center mb-2">Qardan Hasana</h4>
  <?php if ($qh_total_all !== null): ?>
    <div class="text-center text-muted mb-4" style="font-size: 14px;">
      Total: ₹<?php echo format_inr($qh_total_all, 0); ?>
    </div>
  <?php else: ?>
    <div class="mb-4"></div>
  <?php endif; ?>

  <div class="qh-card-container">
    <?php foreach ($qh_schemes as $sc): ?>
      <?php if (!isset($qh_scheme_meta[$sc])) continue; ?>
      <?php $m = $qh_scheme_meta[$sc]; ?>
      <div class="qh-card <?php echo $m['cls']; ?>">
        <span class="qh-card-icon"><i class="<?php echo $m['icon']; ?>"></i></span>
        <div class="qh-card-title"><?php echo htmlspecialchars((string)$m['title']); ?></div>
        <?php if (array_key_exists($sc, $qh_scheme_totals)): ?>
          <div style="margin-top: -0.6rem; margin-bottom: 0.9rem; font-size: 14px; opacity: 0.92;">
            Total: ₹<?php echo format_inr((float)$qh_scheme_totals[$sc], 0); ?>
          </div>
        <?php endif; ?>
        <a href="<?php echo base_url($qh_scheme_base . '/' . $sc); ?>" class="qh-card-link">Go to Scheme</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
