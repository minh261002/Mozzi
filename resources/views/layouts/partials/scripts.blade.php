<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/jquery-ui.min.js"></script>
<script src="/assets/js/datatables.min.js"></script>
<script src="/assets/js/datatable.js"></script>
<script src="/assets/js/tabler.min.js"></script>

{{-- Notyf --}}
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

{{-- Flasher (Notyf) --}}
<script src="/vendor/flasher/flasher.min.js"></script>
<script src="/vendor/flasher/flasher-notyf.min.js"></script>

<script>
    (function() {
        // Initialize Notyf directly
        if (typeof Notyf !== 'undefined') {
            const notyfInstance = new Notyf({
                duration: 3000,
                position: {
                    x: 'center',
                    y: 'top'
                },
                dismissible: true,
                ripple: true
            });

            window.notyf = {
                success: function(message) {
                    notyfInstance.success(message);
                },
                error: function(message) {
                    notyfInstance.error(message);
                }
            };

            console.log('Notyf initialized successfully');
        } else {
            console.error('Notyf library not loaded');
            window.notyf = {
                success: function(msg) {
                    alert('Success: ' + msg);
                },
                error: function(msg) {
                    alert('Error: ' + msg);
                }
            };
        }
    })();
</script>

@stack('scripts')
