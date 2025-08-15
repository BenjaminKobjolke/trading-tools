<?php
// index.php — Einfache Jahresrendite-Berechnung mit Bootstrap 5 (mobil responsive)

// Helper: sichere Eingaben lesen
function read_input(string $key, $default = null) {
    return $_POST[$key] ?? $default;
}

// Helper: deutsche Kommas in Dezimalpunkte umwandeln ("1.234,56" -> 1234.56)
function parse_decimal(?string $s): ?float {
    if ($s === null) return null;
    $s = trim($s);
    if ($s === '') return null;
    $s = str_replace(['\u{00A0}', ' '], '', $s); // geschütztes/gewöhnliches Leerzeichen
    $s = str_replace('.', '', $s);                // Tausenderpunkte entfernen
    $s = str_replace(',', '.', $s);               // Komma -> Punkt
    if (!is_numeric($s)) return null;
    return (float)$s;
}

$amount = null;      // Euro-Betrag
$rate   = null;      // Rendite pro Periode in %
$period = 'monthly'; // 'daily' | 'weekly' | 'monthly'

$result = null;      // Ergebnisdaten
$error  = null;      // Fehlermeldung

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = parse_decimal(read_input('amount'));
    $rate   = parse_decimal(read_input('rate'));
    $period = read_input('period', 'monthly');

    if ($amount === null || $amount < 0) {
        $error = 'Bitte einen gültigen Betrag in Euro eingeben (≥ 0).';
    } elseif ($rate === null) {
        $error = 'Bitte eine gültige Rendite in % pro Periode eingeben.';
    } elseif (!in_array($period, ['daily','weekly','monthly'], true)) {
        $error = 'Bitte eine gültige Periodenart wählen.';
    } else {
        // Perioden pro Jahr bestimmen
        $n = match($period) {
            'daily' => 365,   // Kalendertage
            'weekly' => 52,
            'monthly' => 12,
            default => 12,
        };

        $r = $rate / 100.0;            // z.B. 1.2% => 0.012 pro Periode
        $factor = pow(1 + $r, $n);     // Aufzinsungsfaktor für 1 Jahr
        $ear = $factor - 1;            // Effektive Jahresrendite
        $finalAmount = $amount * $factor;
        $profit = $finalAmount - $amount;

        $result = [
            'n' => $n,
            'factor' => $factor,
            'ear' => $ear,
            'final' => $finalAmount,
            'profit' => $profit,
        ];
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jahresrendite Rechner</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .card { border-radius: 1rem; }
    .form-check { user-select: none; }
    code { background: #f1f3f5; padding: .125rem .375rem; border-radius: .375rem; }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8 col-xl-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h3 mb-3 text-center">Jahresrendite-Rechner</h1>
            <p class="text-muted text-center mb-4">Berechne die effektive Jahresrendite bei täglicher, wöchentlicher oder monatlicher Verzinsung.</p>

            <?php if ($error): ?>
              <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" novalidate>
              <div class="mb-3">
                <label for="amount" class="form-label">Betrag (EUR)</label>
                <div class="input-group">
                  <span class="input-group-text">€</span>
                  <input type="text" class="form-control" id="amount" name="amount"
                         inputmode="decimal" placeholder="z. B. 10.000,00"
                         value="<?php echo $amount !== null ? htmlspecialchars(number_format($amount, 2, ',', '.')) : '' ?>">
                </div>
              </div>

              <div class="row g-3">
                <div class="col-12 col-sm-6">
                  <label for="rate" class="form-label">Rendite pro Periode (%)</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="rate" name="rate"
                           inputmode="decimal" placeholder="z. B. 1,2"
                           value="<?php echo $rate !== null ? htmlspecialchars(number_format($rate, 4, ',', '.')) : '' ?>">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Periode</label>
                  <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="period" id="p_daily" value="daily" <?php echo $period==='daily'?'checked':''; ?>>
                      <label class="form-check-label" for="p_daily">täglich</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="period" id="p_weekly" value="weekly" <?php echo $period==='weekly'?'checked':''; ?>>
                      <label class="form-check-label" for="p_weekly">wöchentlich</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="period" id="p_monthly" value="monthly" <?php echo $period==='monthly'?'checked':''; ?>>
                      <label class="form-check-label" for="p_monthly">monatlich</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Berechnen</button>
              </div>
            </form>

            <?php if ($result): ?>
              <hr class="my-4">
              <h2 class="h5 mb-3">Ergebnis (1 Jahr)</h2>
              <div class="row gy-3">
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-light rounded">
                    <div class="small text-muted">Effektive Jahresrendite</div>
                    <div class="fs-4 fw-semibold"><?php echo number_format($result['ear']*100, 2, ',', '.'); ?> %</div>
                    <div class="small text-muted">(Aufzinsungsfaktor: <?php echo number_format($result['factor'], 6, ',', '.'); ?>)</div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-light rounded">
                    <div class="small text-muted">Endbetrag nach 1 Jahr</div>
                    <div class="fs-4 fw-semibold">€ <?php echo number_format($result['final'], 2, ',', '.'); ?></div>
                    <div class="small text-muted">Gewinn: € <?php echo number_format($result['profit'], 2, ',', '.'); ?></div>
                  </div>
                </div>
              </div>

              <div class="mt-3 text-muted small">
                Annahmen: Zinseszins mit der gewählten Periodizität (<?php echo htmlspecialchars($period); ?> → <?php echo (int)$result['n']; ?> Perioden/Jahr). Formel: <code>Endbetrag = Betrag · (1 + r)<sup>n</sup></code>, mit <code>r</code> = Periodenrendite in Dezimalform, <code>n</code> = Perioden/Jahr.
              </div>
            <?php endif; ?>

            <div class="mt-4 small text-muted">
              Tipp: Verwende Komma für Dezimalstellen (z. B. <em>1,25</em>). Tausenderpunkte sind optional.
            </div>
          </div>
        </div>
        <p class="text-center text-muted mt-3 mb-0 small">© <?php echo date('Y'); ?> Jahresrendite-Rechner</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
