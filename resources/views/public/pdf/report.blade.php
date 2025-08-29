{{-- resources/views/public/pdf/report.blade.php --}}
@php
    // Path background (public/assets/public/report_templates/)
    function bgPath(string $label): string {
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
        return public_path('assets/public/report_templates/'.($map[$label] ?? 'Person.jpg'));
    }
    function safe_text(?string $t): string { return nl2br(e($t ?? '')); }
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  /* ===== Canvas A4 landscape ===== */
  @page { margin: 0; size: A4 landscape; }
  html, body { margin:0; padding:0; }
  body { font-family: "Times New Roman", Times, serif; }

  .page { position: relative; width: 297mm; height: 210mm; overflow: hidden; }
  .bg   { position: absolute; inset: 0; width: 297mm; height: 210mm; }

  /* Util */
  .box   { position:absolute; word-wrap:break-word; overflow-wrap:anywhere; }
  .bold  { font-weight:700; }
  .small { font-size:12px; line-height:1.3; }
  .normal{ font-size:16px; line-height:1.25; }

  /* ================= PERSON (5) – Top 3 + Strength desc ================= */
  /* Base style */
  .p5comp  { color:#fff; text-align:center; font-weight:700; font-size:16px; }
  .p5desc  { color:#fff; font-size:12.5px; line-height:1.35; width:150mm; left:125mm; }
  /* Positions (judul di panel atas: kiri–tengah–kanan) */
  .p5comp2 { top:82mm; left:40mm;  width:75mm; }   /* #2 (kiri)  */
  .p5comp1 { top:75mm; left:111mm; width:75mm; }   /* #1 (tengah)*/
  .p5comp3 { top:85mm; left:182mm; width:75mm; }   /* #3 (kanan) */
  /* Deskripsi 1–3 di panel kanan bawah */
  .p5desc1 { top:127mm; }
  .p5desc2 { top:150mm; }
  .p5desc3 { top:175mm; }

  /* ================= PERSON (6) – Bottom 3 + skor + Weakness ================= */
  .p6name { color:#0B3BBF; font-weight:700; font-size:16px; }
  .p6score{ color:#0B3BBF; font-size:15px; }
  .p6weak { color:#0B3BBF; font-size:12.5px; line-height:1.35; width:90mm; }

  .p6name1{ top:45mm;  left:25mm;  width:140mm; } .p6score1{ top:45mm;  left:170mm; width:30mm; }
  .p6weak1{ top:45mm;  left:195mm; }

  .p6name2{ top:80mm;  left:25mm;  width:140mm; } .p6score2{ top:80mm;  left:170mm; width:30mm; }
  .p6weak2{ top:80mm;  left:195mm; }

  .p6name3{ top:115mm; left:25mm;  width:140mm; } .p6score3{ top:115mm; left:170mm; width:30mm; }
  .p6weak3{ top:115mm; left:195mm; }

  /* ================= PERSON (7) – Improvement Activities ================= */
  .p7act { color:#0B3BBF; font-size:12.5px; line-height:1.35; }
  .p7act1{ top:70mm; left:18mm;  width:90mm; }
  .p7act2{ top:70mm; left:103mm; width:90mm; }
  .p7act3{ top:70mm; left:188mm; width:90mm; }

  /* ================= PERSON (10) – ST-30 Strengths (list) ================= */
  .p10item{ color:#0B3BBF; font-size:16px; left:30mm; width:230mm; }
  .p10i1{ top:45mm; } .p10i2{ top:60mm; } .p10i3{ top:75mm; }
  .p10i4{ top:90mm; } .p10i5{ top:105mm;} .p10i6{ top:120mm;}
  .p10i7{ top:135mm;}

  /* ================= PERSON (11) – ST-30 Weakness (list) ================= */
  .p11item{ color:#0B3BBF; font-size:16px; left:30mm; width:230mm; }
  .p11i1{ top:45mm; } .p11i2{ top:60mm; } .p11i3{ top:75mm; }
  .p11i4{ top:90mm; } .p11i5{ top:105mm;} .p11i6{ top:120mm;}
  .p11i7{ top:135mm;}

  /* ================= PERSON (12) – Training Recommendations ================= */
  .p12tr { color:#0B3BBF; font-size:12.5px; line-height:1.35; }
  .p12tr1{ top:85mm; left:18mm;  width:90mm; }
  .p12tr2{ top:85mm; left:103mm; width:90mm; }
  .p12tr3{ top:85mm; left:188mm; width:90mm; }
</style>
</head>
<body>

@foreach ($pages as $label)
  <div class="page">
    <img class="bg" src="{{ bgPath($label) }}" alt="bg {{ $label }}">

    {{-- PERSON 5 --}}
    @if ($label === 'person 5')
      @if(isset($sjt_top3[1])) <div class="box p5comp p5comp2">{{ $sjt_top3[1]['name'] ?? '' }}</div> @endif
      @if(isset($sjt_top3[0])) <div class="box p5comp p5comp1">{{ $sjt_top3[0]['name'] ?? '' }}</div> @endif
      @if(isset($sjt_top3[2])) <div class="box p5comp p5comp3">{{ $sjt_top3[2]['name'] ?? '' }}</div> @endif

      @if(isset($sjt_top3[0])) <div class="box p5desc p5desc1">{!! safe_text($sjt_top3[0]['strength'] ?? '') !!}</div> @endif
      @if(isset($sjt_top3[1])) <div class="box p5desc p5desc2">{!! safe_text($sjt_top3[1]['strength'] ?? '') !!}</div> @endif
      @if(isset($sjt_top3[2])) <div class="box p5desc p5desc3">{!! safe_text($sjt_top3[2]['strength'] ?? '') !!}</div> @endif
    @endif

    {{-- PERSON 6 --}}
    @if ($label === 'person 6')
      @if(isset($sjt_bottom3[0]))
        <div class="box p6name  p6name1">{{ $sjt_bottom3[0]['name'] ?? '' }}</div>
        <div class="box p6score p6score1">Skor: {{ $sjt_bottom3[0]['score'] ?? '-' }}</div>
        <div class="box p6weak  p6weak1">{!! safe_text($sjt_bottom3[0]['weakness'] ?? '') !!}</div>
      @endif
      @if(isset($sjt_bottom3[1]))
        <div class="box p6name  p6name2">{{ $sjt_bottom3[1]['name'] ?? '' }}</div>
        <div class="box p6score p6score2">Skor: {{ $sjt_bottom3[1]['score'] ?? '-' }}</div>
        <div class="box p6weak  p6weak2">{!! safe_text($sjt_bottom3[1]['weakness'] ?? '') !!}</div>
      @endif
      @if(isset($sjt_bottom3[2]))
        <div class="box p6name  p6name3">{{ $sjt_bottom3[2]['name'] ?? '' }}</div>
        <div class="box p6score p6score3">Skor: {{ $sjt_bottom3[2]['score'] ?? '-' }}</div>
        <div class="box p6weak  p6weak3">{!! safe_text($sjt_bottom3[2]['weakness'] ?? '') !!}</div>
      @endif
    @endif

    {{-- PERSON 7 --}}
    @if ($label === 'person 7')
      @if(isset($reco_activity[0])) <div class="box p7act p7act1">{!! safe_text($reco_activity[0]) !!}</div> @endif
      @if(isset($reco_activity[1])) <div class="box p7act p7act2">{!! safe_text($reco_activity[1]) !!}</div> @endif
      @if(isset($reco_activity[2])) <div class="box p7act p7act3">{!! safe_text($reco_activity[2]) !!}</div> @endif
    @endif

    {{-- PERSON 10 --}}
    @if ($label === 'person 10')
      @foreach ($st30_strengths as $i => $row)
        <div class="box p10item p10i{{ $i+1 }}">{{ ($i+1).'. '.($row->name ?? '') }}</div>
      @endforeach
    @endif

    {{-- PERSON 11 --}}
    @if ($label === 'person 11')
      @foreach ($st30_weakness as $i => $row)
        <div class="box p11item p11i{{ $i+1 }}">{{ ($i+1).'. '.($row->name ?? '') }}</div>
      @endforeach
    @endif

    {{-- PERSON 12 --}}
    @if ($label === 'person 12')
      @if(isset($reco_training[0])) <div class="box p12tr p12tr1">{!! safe_text($reco_training[0]) !!}</div> @endif
      @if(isset($reco_training[1])) <div class="box p12tr p12tr2">{!! safe_text($reco_training[1]) !!}</div> @endif
      @if(isset($reco_training[2])) <div class="box p12tr p12tr3">{!! safe_text($reco_training[2]) !!}</div> @endif
    @endif
  </div>
@endforeach

</body>
</html>
    