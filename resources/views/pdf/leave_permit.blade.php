<!DOCTYPE html>
<html>
<head>
    <title>Surat Izin Cuti - {{ $leaveRequest->user->name }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .header { text-align: center; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 30px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 0; font-size: 14px; }
        .content { margin: 0 30px; }
        .title { text-align: center; margin-bottom: 30px; }
        .title h3 { text-decoration: underline; margin: 0; }
        .title span { font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table td { padding: 8px; vertical-align: top; }
        .label { width: 150px; font-weight: bold; }
        .footer { margin-top: 60px; width: 100%; }
        .footer-content { float: right; width: 250px; text-align: center; }
        .ttd { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PT. PERUSAHAAN ANDA</h2>
        <p>Jl. Contoh No. 123, Jakarta Selatan</p>
        <p>Telp: (021) 555-1234 | Email: hrd@perusahaan.com</p>
    </div>

    <div class="content">
        <div class="title">
            <h3>SURAT IZIN CUTI PEGAWAI</h3>
            <span>Nomor: CUTI/{{ $leaveRequest->id }}/{{ $leaveRequest->created_at->format('m/Y') }}</span>
        </div>
        
        <p>Diberikan izin cuti kepada:</p>
        
        <table class="table">
            <tr><td class="label">Nama</td><td>: {{ $leaveRequest->user->name }}</td></tr>
            <tr><td class="label">Divisi</td><td>: {{ $leaveRequest->user->division ?? '-' }}</td></tr>
            <tr><td class="label">Email</td><td>: {{ $leaveRequest->user->email }}</td></tr>
        </table>

        <p>Detail pengambilan cuti sebagai berikut:</p>
        
        <table class="table" border="1" cellspacing="0" cellpadding="5">
            <tr>
                <td class="label">Jenis Cuti</td>
                <td>{{ ucfirst($leaveRequest->leave_type) }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td>{{ $leaveRequest->start_date->format('d F Y') }} s/d {{ $leaveRequest->end_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Total Durasi</td>
                <td>{{ $leaveRequest->total_days }} Hari Kerja</td>
            </tr>
            <tr>
                <td class="label">Alasan</td>
                <td>{{ $leaveRequest->reason }}</td>
            </tr>
        </table>

        <p>Demikian surat izin ini diterbitkan untuk dipergunakan sebagaimana mestinya.</p>

        <div class="footer">
            <div class="footer-content">
                <p>Jakarta, {{ now()->format('d F Y') }}</p>
                <p>Menyetujui,</p>
                <br>
                <div class="ttd">HRD MANAGER</div>
            </div>
        </div>
    </div>
</body>
</html>