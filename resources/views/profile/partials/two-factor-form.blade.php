<section x-data="{
    enabled: {{ auth()->user()->two_factor_confirmed_at ? 'true' : 'false' }},
    qrCode: null,
    errorMessage: null,
    init() {
        const params = new URLSearchParams(window.location.search);
        const action = params.get('fortify_action');
        if (action === 'enable') this.enableTwoFactor();
        if (action === 'disable') this.disableTwoFactor();
        if (action) {
            params.delete('fortify_action');
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            history.replaceState({}, '', newUrl);
        }
    },
    enableTwoFactor() {
        fetch('/user/two-factor-authentication', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        }).then(response => {
            if (response.status === 423) {
                const redirectTo = encodeURIComponent('/profile?fortify_action=enable');
                window.location.href = '/user/confirm-password?redirect=' + redirectTo;
                return;
            }
            return fetch('/user/two-factor-qr-code', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => this.qrCode = data.svg);
        });
    },
    confirmTwoFactor(code) {
        this.errorMessage = null;
        fetch('/user/confirmed-two-factor-authentication', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code: code }),
        }).then(async response => {
            if (response.status === 423) {
                const redirectTo = encodeURIComponent('/profile?fortify_action=enable');
                window.location.href = '/user/confirm-password?redirect=' + redirectTo;
                return;
            }
            if (!response.ok) {
                const data = await response.json().catch(() => null);
                this.errorMessage = data?.errors?.code?.[0] ?? 'Código inválido. Tente novamente.';
                return;
            }
            this.enabled = true;
            this.qrCode = null;
        });
    },
    disableTwoFactor() {
        fetch('/user/two-factor-authentication', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        }).then(response => {
            if (response.status === 423) {
                const redirectTo = encodeURIComponent('/profile?fortify_action=disable');
                window.location.href = '/user/confirm-password?redirect=' + redirectTo;
                return;
            }
            this.enabled = false;
            this.qrCode = null;
        });
    },
}">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Autenticação em Duas Etapas</h2>
        <p class="mt-1 text-sm text-gray-600">
            Adicione uma camada extra de segurança usando um app autenticador (Google Authenticator, Authy).
        </p>
    </header>

    <div class="mt-4" x-show="!enabled && !qrCode">
        <button type="button" @click="enableTwoFactor()" class="bg-blue-600 text-white px-4 py-2 rounded">
            Ativar 2FA
        </button>
    </div>

    <div class="mt-4" x-show="qrCode" x-html="qrCode"></div>

    <div class="mt-4" x-show="qrCode">
        <label class="block text-sm">Digite o código do app autenticador:</label>
        <input type="text" x-ref="code" class="border-gray-300 rounded mt-1" required>
        <button type="button" @click="confirmTwoFactor($refs.code.value)" class="bg-green-600 text-white px-4 py-2 rounded mt-2">
            Confirmar
        </button>
        <p x-show="errorMessage" x-text="errorMessage" class="text-red-600 text-sm mt-2"></p>
    </div>

    <div class="mt-4" x-show="enabled">
        <p class="text-green-600 font-medium">✓ 2FA ativado</p>
        <button type="button" @click="disableTwoFactor()" class="text-red-600 mt-2">
            Desativar 2FA
        </button>
    </div>
</section>