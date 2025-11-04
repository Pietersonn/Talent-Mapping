{{-- resources/views/public/pdf/report.blade.php --}}
@php
    // Hindari re-declare
    if (!function_exists('bgSrc')) {
        /**
         * Kembalikan SRC yang valid untuk DomPDF:
         * file:// + path dengan forward slash (Windows safe)
         */
        function bgSrc(string $label): string
        {
            $map = [
                'person'    => 'Person.jpg',
                'person 2'  => 'Person (2).jpg',
                'person 3'  => 'Person (3).jpg',
                'person 4'  => 'Person (4).jpg',
                'user'      => 'User.jpg',
                'person 5'  => 'Person (5).jpg',
                'person 6'  => 'Person (6).jpg',
                'person 7'  => 'Person (7).jpg',
                'person 8'  => 'Person (8).jpg',
                'person 9'  => 'Person (9).jpg',
                'person 10' => 'Person (10).jpg',
                'person 11' => 'Person (11).jpg',
                'person 12' => 'Person (12).jpg',
                'person 13' => 'Person (13).jpg',
                'person 14' => 'Person (14).jpg',
                'person 15' => 'Person (15).jpg',
            ];
            $p = public_path('assets/public/report_templates/'.($map[$label] ?? 'Person.jpg'));
            return 'file://'.str_replace('\\','/', $p);
        }
    }
    if (!function_exists('safe_text')) {
        function safe_text(?string $t): string
        {
            return nl2br(e($t ?? ''));
        }
    }

    // Kode tipologi (untuk halaman 9)
    $strengthCodes = collect($st30_strengths ?? [])->pluck('code')->filter()->implode(', ');
    $weaknessCodes = collect($st30_weakness ?? [])->pluck('code')->filter()->implode(', ');
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  @page { margin: 0; size: A4 landscape; }
  html, body { margin:0; padding:0; }
  body { font-family: "Times New Roman", Times, serif; }

  .page { position: relative; width: 297mm; height: 210mm; overflow: hidden; }
  .bg   { position: absolute; inset: 0; width: 297mm; height: 210mm; }

  .box{ position:absolute; word-wrap:break-word; overflow-wrap:anywhere; }

  /* ---------- PERSON (cover) ---------- */
  .p1-name{
    color:#fff; font-weight:700; font-size:32pt; line-height:1.1;
    left:22mm; top:138mm; width:220mm; text-shadow:0 1px 2px rgba(0,0,0,.25);
  }

  /* ---------- PERSON (5) ---------- */
  .p5comp { color:#fff; font-weight:700; font-size:16px; text-align:center; }
  .p5desc { color:#fff; font-size:16px; line-height:1.35; width:150mm; left:125mm; font-weight:700 }
  .p5comp2{ top:82mm; left:45mm;  width:65mm; }
  .p5comp1{ top:73mm; left:115mm; width:65mm; }
  .p5comp3{ top:83mm; left:188mm; width:65mm; }
  .p5desc1{ top:128mm; left:127mm; }
  .p5desc2{ top:151mm; left:127mm; }
  .p5desc3{ top:175mm; left:127mm; }

  /* ---------- PERSON (6) ---------- */
  .p6name { color:#0B3BBF; font-weight:700; font-size:17px; width:130mm; }
  .p6score{ color:#0B3BBF; font-weight:700; font-size:24px; width:30mm; text-align:left; }
  .p6weak { color:#fff; font-size:16px; line-height:1.45; width:120mm; font-weight:700 }
  .p6name1 { top: 96mm; left:32mm; }  .p6score1{ top:102.5mm; left:52mm; }  .p6weak1 { top:92mm;  left:175mm; width:110mm; }
  .p6name2 { top:130mm; left:32mm; }  .p6score2{ top:137.5mm; left:52mm; }  .p6weak2 { top:128mm; left:175mm; width:110mm; }
  .p6name3 { top:167mm; left:32mm; }  .p6score3{ top:173.5mm; left:52mm; }  .p6weak3 { top:162mm; left:175mm; width:110mm; }

  /* ---------- PERSON (7) ---------- */
  .p7act  { color:#fff; font-size:17px; font-weight:400; line-height:1.35; text-align:left; }
  .p7act1 { top:65mm; left:32mm;  width:65mm; }
  .p7act2 { top:65mm; left:116mm; width:65mm; }
  .p7act3 { top:65mm; left:199mm; width:65mm; }

  /* ---------- PERSON (9) ---------- */
  .p9strong { color:#0B3BBF; font-weight:700; font-size:24px; line-height:1.35; width:110mm; left:180mm; top:71mm; }
  .p9weak   { color:#0B3BBF; font-weight:700; font-size:24px; line-height:1.35; width:110mm; left:180mm; top:135mm; }

  /* ---------- PERSON (10) ---------- */
  .p10name-base { color:#fff; font-weight:700; font-size:20px; left:45mm; width:65mm; line-height:1.2; }
  .p10desc-base {
    color:#fff; font-size:17px; line-height:1.35;
    left:87mm; width:180mm;
    text-align:justify;        /* rata kanan-kiri */
    text-justify:inter-word;   /* distribusi spasi antar kata */
    padding-right:2mm;         /* jarak aman sisi kanan */
    word-wrap:break-word;
    overflow-wrap:anywhere;
  }
  .p10-name-1{ top:49mm; } .p10-desc-1{ top:49mm; }
  .p10-name-2{ top:70mm; } .p10-desc-2{ top:70mm; }
  .p10-name-3{ top:91mm; } .p10-desc-3{ top:91mm; }
  .p10-name-4{ top:114mm;} .p10-desc-4{ top:114mm;}
  .p10-name-5{ top:137mm;} .p10-desc-5{ top:137mm;}
  .p10-name-6{ top:159mm;} .p10-desc-6{ top:159mm;}
  .p10-name-7{ top:182mm;} .p10-desc-7{ top:182mm;}

  /* ---------- PERSON (11) ---------- */
  .p11name-base { color:#fff; font-weight:700; font-size:20px; left:45mm; width:65mm; line-height:1.2; }
  .p11desc-base {
    color:#fff; font-size:17px; line-height:1.35;
    left:87mm; width:180mm;
    text-align:justify;
    text-justify:inter-word;
    padding-right:2mm;
    word-wrap:break-word;
    overflow-wrap:anywhere;
  }
  .p11-name-1{ top:49mm; } .p11-desc-1{ top:49mm; }
  .p11-name-2{ top:70mm; } .p11-desc-2{ top:70mm; }
  .p11-name-3{ top:91mm; } .p11-desc-3{ top:91mm; }
  .p11-name-4{ top:114mm;} .p11-desc-4{ top:114mm;}
  .p11-name-5{ top:137mm;} .p11-desc-5{ top:137mm;}
  .p11-name-6{ top:159mm;} .p11-desc-6{ top:159mm;}
  .p11-name-7{ top:182mm;} .p11-desc-7{ top:182mm;}

  /* ---------- PERSON (12) ---------- */
  .p12tr  { color:black; font-size:14px; line-height:1.35; }
  .p12tr1 { top:80mm; left:40mm;  width:60mm; }
  .p12tr2 { top:80mm; left:120mm; width:60mm; }
  .p12tr3 { top:80mm; left:200mm; width:60mm; }
</style>
</head>
<body>

@foreach ($pages as $label)
  <div class="page">
    <img class="bg" src="{{ bgSrc($label) }}" alt="bg {{ $label }}">

    {{-- PERSON (cover) --}}
    @if ($label === 'person')
      <div class="box p1-name">{{ $user['name'] ?? '' }}</div>
    @endif

    {{-- PERSON (5) --}}
    @if ($label === 'person 5')
      @if(isset($sjt_top3[1])) <div class="box p5comp p5comp2">{{ $sjt_top3[1]['name'] ?? '' }}</div> @endif
      @if(isset($sjt_top3[0])) <div class="box p5comp p5comp1">{{ $sjt_top3[0]['name'] ?? '' }}</div> @endif
      @if(isset($sjt_top3[2])) <div class="box p5comp p5comp3">{{ $sjt_top3[2]['name'] ?? '' }}</div> @endif

      @if(isset($sjt_top3[0])) <div class="box p5desc p5desc1">{!! safe_text($sjt_top3[0]['strength'] ?? '') !!}</div> @endif
      @if(isset($sjt_top3[1])) <div class="box p5desc p5desc2">{!! safe_text($sjt_top3[1]['strength'] ?? '') !!}</div> @endif
      @if(isset($sjt_top3[2])) <div class="box p5desc p5desc3">{!! safe_text($sjt_top3[2]['strength'] ?? '') !!}</div> @endif
    @endif

    {{-- PERSON (6) --}}
    @if ($label === 'person 6')
      @if(isset($sjt_bottom3[0]))
        <div class="box p6name  p6name1">{{ $sjt_bottom3[0]['name'] ?? '' }}</div>
        <div class="box p6score p6score1">{{ $sjt_bottom3[0]['score'] ?? '-' }}</div>
        <div class="box p6weak  p6weak1">{!! safe_text($sjt_bottom3[0]['weakness'] ?? '') !!}</div>
      @endif
      @if(isset($sjt_bottom3[1]))
        <div class="box p6name  p6name2">{{ $sjt_bottom3[1]['name'] ?? '' }}</div>
        <div class="box p6score p6score2">{{ $sjt_bottom3[1]['score'] ?? '-' }}</div>
        <div class="box p6weak  p6weak2">{!! safe_text($sjt_bottom3[1]['weakness'] ?? '') !!}</div>
      @endif
      @if(isset($sjt_bottom3[2]))
        <div class="box p6name  p6name3">{{ $sjt_bottom3[2]['name'] ?? '' }}</div>
        <div class="box p6score p6score3">{{ $sjt_bottom3[2]['score'] ?? '-' }}</div>
        <div class="box p6weak  p6weak3">{!! safe_text($sjt_bottom3[2]['weakness'] ?? '') !!}</div>
      @endif
    @endif

    {{-- PERSON (7) --}}
    @if ($label === 'person 7')
      @if(isset($reco_activity[0])) <div class="box p7act p7act1">{!! safe_text($reco_activity[0]) !!}</div> @endif
      @if(isset($reco_activity[1])) <div class="box p7act p7act2">{!! safe_text($reco_activity[1]) !!}</div> @endif
      @if(isset($reco_activity[2])) <div class="box p7act p7act3">{!! safe_text($reco_activity[2]) !!}</div> @endif
    @endif

    {{-- PERSON (9) --}}
    @if ($label === 'person 9')
      <div class="box p9strong">{{ $strengthCodes }}</div>
      <div class="box p9weak">{{ $weaknessCodes }}</div>
    @endif

    {{-- PERSON (10) --}}
    @if ($label === 'person 10')
      @foreach ($st30_strengths as $i => $row)
        @php $k = $i + 1; if ($k > 7) break; @endphp
        <div class="box p10name-base p10-name-{{ $k }}">
          {{ $row->name ?? '' }}<br>({{ $row->code ?? '' }})
        </div>
        <div class="box p10desc-base p10-desc-{{ $k }}">{!! safe_text($row->desc ?? '') !!}</div>
      @endforeach
    @endif

    {{-- PERSON (11) --}}
    @if ($label === 'person 11')
      @foreach ($st30_weakness as $i => $row)
        @php $k = $i + 1; if ($k > 7) break; @endphp
        <div class="box p11name-base p11-name-{{ $k }}">
          {{ $row->name ?? '' }}<br>({{ $row->code ?? '' }})
        </div>
        <div class="box p11desc-base p11-desc-{{ $k }}">{!! safe_text($row->desc ?? '') !!}</div>
      @endforeach
    @endif

    {{-- PERSON (12) --}}
    @if ($label === 'person 12')
      @if(isset($reco_training[0])) <div class="box p12tr p12tr1">{!! safe_text($reco_training[0]) !!}</div> @endif
      @if(isset($reco_training[1])) <div class="box p12tr p12tr2">{!! safe_text($reco_training[1]) !!}</div> @endif
      @if(isset($reco_training[2])) <div class="box p12tr p12tr3">{!! safe_text($reco_training[2]) !!}</div> @endif
    @endif

  </div>
@endforeach

</body>
</html>
