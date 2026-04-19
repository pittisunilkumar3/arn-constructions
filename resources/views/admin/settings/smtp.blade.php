@extends('admin.layout')
@section('title', 'SMTP Mail Settings')
@section('page-title', 'SMTP Mail Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="fas fa-envelope me-2" style="color: var(--primary);"></i>SMTP / Mail Settings</h4>
</div>

<form action="{{ route('admin.smtp.update') }}" method="POST" id="smtpForm">
    @csrf @method('PUT')

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Enable/Disable SMTP -->
    <div class="form-card mb-4">
        <h5 class="mb-3"><i class="fas fa-power-off me-2"></i>SMTP Status</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="hidden" name="smtp_enabled" value="0">
                    <input type="checkbox" name="smtp_enabled" value="1" class="form-check-input" id="smtpEnabled" style="width:50px;height:25px;" {{ ($settings['smtp_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                    <label class="form-check-label ms-2" for="smtpEnabled" style="font-size:15px;font-weight:500;">
                        {{ ($settings['smtp_enabled'] ?? '0') === '1' ? '✅ SMTP Enabled' : '⬜ SMTP Disabled' }}
                    </label>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge {{ ($settings['smtp_enabled'] ?? '0') === '1' ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                    Current: {{ ($settings['smtp_enabled'] ?? '0') === '1' ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <!-- SMTP Configuration -->
    <div id="smtpFields">
        <div class="form-card mb-4">
            <h5 class="mb-3"><i class="fas fa-server me-2"></i>SMTP Server Configuration</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">SMTP Host *</label>
                    <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" placeholder="e.g., smtp.gmail.com">
                    <small class="text-muted">Gmail: smtp.gmail.com | Outlook: smtp-mail.outlook.com</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">SMTP Port *</label>
                    <input type="number" name="smtp_port" class="form-control" value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}" placeholder="587">
                    <small class="text-muted">TLS: 587 | SSL: 465</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Encryption *</label>
                    <select name="smtp_encryption" class="form-select">
                        <option value="tls" {{ ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ ($settings['smtp_encryption'] ?? '') === '' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">SMTP Username *</label>
                    <input type="text" name="smtp_username" class="form-control" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" placeholder="e.g., your-email@gmail.com">
                    <small class="text-muted">Your full email address</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">SMTP Password *</label>
                    <div class="input-group">
                        <input type="password" name="smtp_password" id="smtpPassword" class="form-control" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}" placeholder="Enter SMTP password / App Password">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <small class="text-muted">For Gmail, use an <a href="https://myaccount.google.com/apppasswords" target="_blank">App Password</a></small>
                </div>
            </div>
        </div>

        <div class="form-card mb-4">
            <h5 class="mb-3"><i class="fas fa-paper-plane me-2"></i>Sender Information</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">From Email Address *</label>
                    <input type="email" name="mail_from_address" class="form-control" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="e.g., info@anrconstructions.com">
                    <small class="text-muted">Email shown as the sender</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">From Name</label>
                    <input type="text" name="mail_from_name" class="form-control" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? 'ARN Constructions') }}" placeholder="ARN Constructions">
                </div>
            </div>
        </div>

        <!-- Test Email -->
        <div class="form-card mb-4" style="border: 2px dashed var(--primary); background: #fffdf5;">
            <h5 class="mb-3"><i class="fas fa-vial me-2"></i>Test SMTP Connection</h5>
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="email" id="testEmail" class="form-control" placeholder="Enter email to send a test message" value="{{ Auth::user()->email }}">
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-sm-primary w-100 py-2" onclick="sendTestEmail()" id="testBtn">
                        <i class="fas fa-paper-plane me-2"></i>Send Test Email
                    </button>
                </div>
                <div class="col-12" id="testResult" style="display:none;"></div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-sm-primary px-4 py-2"><i class="fas fa-save me-2"></i>Save SMTP Settings</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4 py-2">Cancel</a>
    </div>
</form>
@endsection

@section('scripts')
<script>
// Toggle SMTP fields visibility
const smtpEnabled = document.getElementById('smtpEnabled');
const smtpFields = document.getElementById('smtpFields');

function toggleSmtpFields() {
    smtpFields.style.display = smtpEnabled.checked ? 'block' : 'none';
    smtpEnabled.nextElementSibling.nextElementSibling.textContent = smtpEnabled.checked ? '✅ SMTP Enabled' : '⬜ SMTP Disabled';
    const badge = smtpEnabled.closest('.row').querySelector('.badge');
    if (badge) {
        badge.className = 'badge px-3 py-2 ' + (smtpEnabled.checked ? 'bg-success' : 'bg-secondary');
        badge.textContent = 'Current: ' + (smtpEnabled.checked ? 'Active' : 'Inactive');
    }
}
smtpEnabled.addEventListener('change', toggleSmtpFields);
toggleSmtpFields();

// Toggle password visibility
function togglePassword() {
    const pw = document.getElementById('smtpPassword');
    const icon = document.getElementById('toggleIcon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        pw.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Send test email via AJAX
function sendTestEmail() {
    const email = document.getElementById('testEmail').value;
    const resultDiv = document.getElementById('testResult');
    const btn = document.getElementById('testBtn');

    if (!email) {
        resultDiv.style.display = 'block';
        resultDiv.innerHTML = '<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Please enter an email address.</div>';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';

    fetch('{{ route('admin.smtp.test') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
    })
    .then(r => r.json())
    .then(data => {
        resultDiv.style.display = 'block';
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success mb-0"><i class="fas fa-check-circle me-2"></i>' + data.message + '</div>';
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-times-circle me-2"></i>' + data.message + '</div>';
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Test Email';
    })
    .catch(err => {
        resultDiv.style.display = 'block';
        resultDiv.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-times-circle me-2"></i>An error occurred. Please save settings first and try again.</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Test Email';
    });
}
</script>
@endsection
